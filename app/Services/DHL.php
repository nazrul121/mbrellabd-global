<?php

namespace App\Services;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Dhl_shipment;
use App\Models\Order;
use DateTimeZone;
use DateTime;

class DHL
{
    function get_rates(){
        $acNumber = env('DHL_Ac_number');
        $auth = base64_encode(env('DHL_API_KEY').':'.env('DHL_SECRATE'));
        $messageReferenceDate = gmdate("Y-m-d\TH:i:s\Z");

        // dd($acNumber, $auth);
        $originCountryCode = 'BD';
        $originCityName = 'Dhaka';
        $destinationCountryCode = 'US';
        $destinationCityName = 'New York';
        $weight = 1.8;
        $length = 5;
        $width = 5;
        $height = 4;
        $plannedShippingDate = date('Y-m-d');
        $isCustomsDeclarable = 'false';

        // Define the request URL and parameters
        $url = 'https://express.api.dhl.com/mydhlapi/test/rates';
        $params = [
            'accountNumber' => $acNumber,
            'originCountryCode' => $originCountryCode,
            'originCityName' => $originCityName,
            'destinationCountryCode' => $destinationCountryCode,
            'destinationCityName' => $destinationCityName,
            'weight' => $weight,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'plannedShippingDate' => $plannedShippingDate,
            'isCustomsDeclarable' => $isCustomsDeclarable,
            'unitOfMeasurement' => 'metric',
        ];

        // Define the request headers
        $headers = [
            'Message-Reference' => bin2hex(random_bytes(14)),
            'Message-Reference-Date' => $messageReferenceDate,
            'Plugin-Name' => 'MyDHL Plugin',
            'Plugin-Version' => '1.0',
            'Shipping-System-Platform-Name' => 'Laravel',
            'Shipping-System-Platform-Version' => '8.8',
            'Webstore-Platform-Name' => 'CustomWebstore',
            'Webstore-Platform-Version' => '1.0',
            'Authorization' => 'Basic '.$auth,
        ];

        // Make the GET request
        $response = Http::withHeaders($headers)->get($url, $params);

        // Output the response body
        return $response->body();

    }

    function create_shipment(Order $order){
        // $order = Order::where('id',$orderId)->first();
        $dollarRate = \DB::table('dollar_rate_order')->where('order_id',$order->id)->first();
        $exchangeRate = $dollarRate->value;
      
        // dd($order->shipping_address->country_id);
        $date = new DateTime(date('Y-m-d\TH:i:s'), new DateTimeZone('Asia/Dhaka'));
        $formattedDate = $date->format('Y-m-d\TH:i:s \G\M\T+01:00');

        $auth = base64_encode(env('DHL_API_KEY').':'.env('DHL_SECRATE'));
        $messageReferenceDate = gmdate("Y-m-d\TH:i:s\Z");

        //if(env('APP_ENV')=='local'){
            //$endpoint = 'https://express.api.dhl.com/mydhlapi/test/shipments?strictValidation=false&bypassPLTError=false&validateDataOnly=false';
        //}else{
            //$endpoint = 'https://express.api.dhl.com/mydhlapi/shipments?strictValidation=false&bypassPLTError=false&validateDataOnly=false';
        //}

 	$endpoint = 'https://express.api.dhl.com/mydhlapi/shipments?strictValidation=false&bypassPLTError=false&validateDataOnly=false';


        $orderItems = [];
        $avgWeight = 0;
        $totalNetWeight = $totalWeight = 0.01;

        foreach($order->order_items()->get() as $key=>$item){  
            $totalWeight += $item->gross_weight;  
            if($item->net_weight !=null){
                $totalNetWeight += $item->net_weight;  
            }

            if($item->vat_type=='excluding'){
                $vatExcl = ($item->vat / 100) * $item->discount_price;
                $vatAmount = ($item->vat / 100) * $item->discount_price;
            }else{
                $vatAmount = ($item->vat * ($item->discount_price * $item->qty) ) / ($item->vat + 100);
                $vatExcl = 0;
            }
            $totalVat[] = $vatExcl;

            $amountInUSD =  ($item->discount_price / $exchangeRate)+$vatExcl;
            $subTotalInUSD[] = $amountInUSD;

            
            $orderItems[] = [
                "number"=> $key + 1,
                "description"=> $item->product->title,
                "price"=> (float) number_format($amountInUSD, 2),
                "quantity"=>[
                  "value"=> (int) $item->qty,
                  "unitOfMeasurement"=> 'PCS'
                ],

                "commodityCodes"=>  [
                  [
                    "typeCode"=>  "outbound",
                    "value"=>  "84713000"
                  ]
                ],

                "exportReasonType"=> "permanent",
                "manufacturerCountry"=> "BD",
                "exportControlClassificationNumber"=> "US123456789",
                "weight"=>[
                  "netValue"=> (float) $item->net_weight,
                  "grossValue"=>(float) $item->gross_weight
                ],

                "isTaxesPaid"=> true,
                // "additionalInformation"=> [
                //   "450pages"
                // ],

                // "customerReferences"=> [
                //   [
                //       "typeCode"=> "AFE",
                //       "value"=> "1299210"
                //   ]
                // ],
                "customsDocuments"=> [
                  [
                      "typeCode"=> "COO",
                      "value"=> "MyDHLAPI - LN#1-CUSDOC-001"
                  ]
                ]
            ];

        }

        // dd($orderItems, $order->order_items->count());

        $smallBoxDimensions = [ "length" => 12.5,   "width" => 12, "height" => 3.5];
        $largeBoxDimensions = [ "length" => 17.5, "width" => 17.5, "height" => 4.5 ];
        // Call the function to calculate how many boxes are needed
        $packagingData = $this->calculateBoxes($totalWeight, 1.5, 3.5, $smallBoxDimensions, $largeBoxDimensions);
        


        // dd($orderItems,$totalNetWeight);
        // dd($order->shipping_address->country->short_code);
        // Set the shipment details in the request body
        $data = [
            "plannedShippingDateAndTime" => $formattedDate,
            "pickup" => [
                "isRequested" => false
            ],
                        
            "productCode"=> "P",
            "localProductCode"=> "P",
            "getRateEstimates"=> false,
            "accounts" => [
                [
                    "typeCode"=> "shipper",
                    "number"=> env('DHL_Ac_number')
                ]
            ],
            "valueAddedServices"=> [
               [
                "serviceCode"=> "II",
                "value"=> 10,
                "currency"=> "USD"
               ]
            ],

            "outputImageProperties"=> [
                "printerDPI"=> 300,
                "encodingFormat"=> "pdf",
                "imageOptions"=> [
                  [
                    "typeCode"=> "invoice",
                    "templateName"=> "COMMERCIAL_INVOICE_P_10",
                    "isRequested"=> true,
                    "invoiceType"=> "commercial",
                    "languageCode"=> "eng",
                    "languageCountryCode"=> "US"
                  ],
                  [
                    "typeCode"=> "waybillDoc",
                    "templateName"=> "ARCH_8x4",
                    "isRequested"=> true,
                    "hideAccountNumber"=> false,
                    "numberOfCopies"=> 1
                  ],
                  [
                    "typeCode"=>"label",
                    "templateName"=> "ECOM26_84_001",
                    "renderDHLLogo"=> true,
                    "fitLabelsToA4"=> false
                  ]
                ],
                "splitTransportAndWaybillDocLabels"=> true,
                "allDocumentsInOneImage"=> false,
                "splitDocumentsByPages"=> false,
                "splitInvoiceAndReceipt"=> true,
                "receiptAndLabelsInOneImage"=> false
            ] ,

            "customerDetails"=> [
                "shipperDetails"=>[
                    "postalAddress"=> [
                        "postalCode"=> "1229",
                        "cityName"=> "Dhaka",
                        "countryCode"=> "BD",
                        "addressLine1"=> "House 19, Road 3, Sector 3, Uttara, Dkaka",
                        "countryName"=> "BANGLADESH"
                    ],
                    "contactInformation"=> [
                        "email"=> "info@mbrella.ltd",
                        "phone"=> "01999080846",
                        "mobilePhone"=> "01999080846",
                        "companyName"=> "MBRELLA LTD",
                        "fullName"=> "A Lifestyle Clothing Brand"
                    ],
                    // "registrationNumbers"=> [
                    //     [
                    //         "typeCode"=> "SDT",
                    //         "number"=> "CN123456789",
                    //         "issuerCountryCode"=> "CN"
                    //     ]
                    // ],
                    // "bankDetails"=> [
                    //     [
                    //         "name"=> "Bank of China",
                    //         "settlementLocalCurrency"=> "RMB",
                    //         "settlementForeignCurrency"=> "USD"
                    //     ]
                    // ],
                    // "typeCode"=> "business"
                ],
                "receiverDetails"=> [
                    // "postalAddress"=>[
                    //     "cityName"=> "Graford",
                    //     "countryCode"=> "US",
                    //     "postalCode"=> "76449",
                    //     "addressLine1"=> "116 Marine Dr",
                    //     "countryName"=> "UNITED STATES OF AMERICA"
                    // ],
                    "postalAddress"=>[
                        "cityName"=> $order->ship_district,
                        "countryCode"=> $order->country->short_code,
                        "postalCode"=> $order->ship_postCode,
                        "addressLine1"=> $order->ship_address,
                        "countryName"=> $order->country->name
                    ],
                    "contactInformation"=>[
                        "email"=>  $order->ship_email??'no@email.com',
                        "phone"=> $order->ship_phone,
                        "mobilePhone"=> $order->ship_phone,
                        "companyName"=> "Home",
                        "fullName"=> $order->ship_first_name.' '. $order->ship_last_name
                    ],
                    // "registrationNumbers"=> [
                    //     [
                    //         "typeCode"=> "SSN",
                    //         "number"=> "US123456789",
                    //         "issuerCountryCode"=> "US"
                    //     ]
                    // ],
                    // "bankDetails"=> [
                    //     [
                    //         "name"=> "Bank of America",
                    //         "settlementLocalCurrency"=> "USD",
                    //         "settlementForeignCurrency"=> "USD"
                    //     ]
                    // ],
                    // "typeCode"=> "business"
                ]
            ],

            "content"=> [
                "packages" => $packagingData,

                "isCustomsDeclarable"=> true,
                "declaredValue"=> 120,
                "declaredValueCurrency"=> "USD",
                "exportDeclaration"=> [
                    // product items 
                    "lineItems"=> $orderItems ,
                    "invoice"=> [
                        "number"=> (string) $order->invoice_id,
                        "date"=>  $order->order_date,
                        "instructions"=> [
                            "Handle with care"
                        ],

                        "totalNetWeight"=> (float) number_format($totalNetWeight, 3),
                        "totalGrossWeight"=> (float) number_format($totalWeight, 3),
                        "customerReferences"=> [
                            [
                                "typeCode"=> "UCN",
                                "value"=> "UCN-783974937"
                            ],
                            [
                                "typeCode"=> "CN",
                                "value"=> "CUN-76498376498"
                            ],
                            [
                                "typeCode"=> "RMA",
                                "value"=> "MyDHLAPI-TESTREF-001"
                            ]
                        ],
                        "termsOfPayment"=> "100 days",
                        "indicativeCustomsValues"=> [
                            "importCustomsDutyValue"=> 150.57,
                            "importTaxesValue"=> 49.43
                        ]
                    ],
                
                    "additionalCharges"=> [
                        [
                            "value"=> 10,
                            "caption"=> "fee",
                            "typeCode"=> "freight"
                        ],
                        [
                            "value"=> 20,
                            "caption"=> "freight charges",
                            "typeCode"=> "other"
                        ],
                        [
                            "value"=> 10,
                            "caption"=> "ins charges",
                            "typeCode"=> "insurance"
                        ],
                        [
                            "value"=> 7,
                            "caption"=> "rev charges",
                            "typeCode"=> "reverse_charge"
                        ]
                    ],

                    "destinationPortName"=> "New York Port",
                    "placeOfIncoterm"=> "ShenZhen Port",
                    "payerVATNumber"=> "12345ED",
                    "recipientReference"=> "01291344",
                    "exporter"=> [
                        "id"=> "121233",
                        "code"=> "S"
                    ],
                    "packageMarks"=> "Fragile glass bottle",
                    "declarationNotes"=> [
                        ["value"=> "up to three declaration notes"]
                    ],
                    "exportReference"=> "export reference",
                    "exportReason"=> "export reason",
                    "exportReasonType"=> "permanent",
                    "licenses"=> [
                        [
                            "typeCode"=> "export",
                            "value"=> "061993/2022"
                        ]
                    ],
                    "shipmentType"=> "personal",
                    "customsDocuments"=> [
                        [
                        "typeCode"=> "INV",
                            "value"=> "MyDHLAPI - CUSDOC-001"
                        ]
                    ]
                ],
                "description"=> "Shipment",
                "USFilingTypeValue"=> "12345",
                "incoterm"=> "DAP",
                "unitOfMeasurement"=> "metric"
            ],

            "shipmentNotification"=> [
                [
                    "typeCode"=> "email",
                    "receiverId"=> $order->ship_email??'no@email.com',
                    "languageCode"=> "eng",
                    "languageCountryCode"=> "UK",
                    "bespokeMessage"=> "Hello ".$order->ship_first_name.' '.$order->ship_last_name.'!<br/> You got an order '
                ]
              ],
              "getTransliteratedResponse"=> false,
              "estimatedDeliveryDate"=>[
                "isRequested"=> true,
                "typeCode"=> "QDDC"
              ],
              "getAdditionalInformation"=> [
               [
                "typeCode"=> "pickupDetails",
                "isRequested"=> true
               ]
              ]

        ];
        
        $jsonData = json_encode($data) ;
        // dd($jsonData);
       
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'Message-Reference' =>  bin2hex(random_bytes(14)),
            'Message-Reference-Date' =>  $messageReferenceDate,
            'Plugin-Name' => 'MyDHL Plugin', // Add plugin name if applicable
            'Plugin-Version' => '1.0', // Add plugin version if applicable
            'Shipping-System-Platform-Name' => 'laravel', // Add platform name if applicable
            'Shipping-System-Platform-Version' => '8.8', // Add platform version if applicable
            'Webstore-Platform-Name' => 'CustomWebstore', // Add webstore platform name if applicable
            'Webstore-Platform-Version' => '1.0', // Add webstore platform version if applicable
            'Authorization' => 'Basic YXBSMnVMNnJGM2FNN3Q6TV4wZFUjMHdXITlvVCMzaw==',
            'Content-Type' => 'application/json',
            'Cookie' => 'BIGipServer~WSB~pl_wsb-express-cbj.dhl.com_443=494676167.64288.0000',
        ])->post($endpoint,$data);
        
        // Check the response
        
        $returnData = json_decode($response, true);
        // dd($returnData);

        if ($response->successful()) {
            Dhl_shipment::create([
                'order_id'=>$order->id,
                'trackingNumber'=>$response['shipmentTrackingNumber'],
                'documents'=> json_encode($response['documents']),
                'shipmentDetails'=>json_encode($response['shipmentDetails']),
                'estimatedDeliveryDate'=>json_encode($response['estimatedDeliveryDate']),
                'all_returns'=> $response
            ]);
        }else{
            Dhl_shipment::create([
                'order_id'=>$order->id,
                'all_returns'=> $response
            ]);
        }
    
    }


    function create_pickup(Order $order){
        if($order->dhl_shipment->dispatchConfirmationNumbers !=null){
            dd('alert','This order already has generated a pick up request!');
        }
        $date = new DateTime(date('Y-m-d\TH:i:s'), new DateTimeZone('Asia/Dhaka'));
        $formattedDate = $date->format('Y-m-d\TH:i:s \G\M\T+01:00');

        $auth = base64_encode(env('DHL_API_KEY').':'.env('DHL_SECRATE'));
        $messageReferenceDate = gmdate("Y-m-d\TH:i:s\Z");

        //if(env('APP_ENV')=='local'){
           // $endpoint = 'https://express.api.dhl.com/mydhlapi/test/pickups';
        //}else{
            //$endpoint = 'https://express.api.dhl.com/mydhlapi/pickups';
        //}

	 $endpoint = 'https://express.api.dhl.com/mydhlapi/pickups';

        

        $orderItems = [];
        $avgWeight = 0;
        $totalNetWeight = $totalWeight = 0.01;

        foreach($order->order_items()->get() as $key=>$item){  
            $totalWeight += $item->gross_weight;  
            if($item->net_weight !=null){
                $totalNetWeight += $item->net_weight;  
            }

            if($item->vat_type=='excluding'){
                $vatExcl = ($item->vat / 100) * $item->discount_price;
                $vatAmount = ($item->vat / 100) * $item->discount_price;
            }else{
                $vatAmount = ($item->vat * ($item->discount_price * $item->qty) ) / ($item->vat + 100);
                $vatExcl = 0;
            }
            $totalVat[] = $vatExcl;
        }

        $smallBoxDimensions = [ "length" => 12.5,   "width" => 12, "height" => 3.5];
        $largeBoxDimensions = [ "length" => 17.5, "width" => 17.5, "height" => 4.5 ];
        // Call the function to calculate how many boxes are needed
        $packagingData = $this->calculateBoxesForPickup($totalWeight, 1.5, 3.5, $smallBoxDimensions, $largeBoxDimensions);

        $data = [
            "plannedPickupDateAndTime"=> $formattedDate,
            "closeTime"=> "18:00",
            "location"=> "reception",
            "locationType"=> "business",
            "accounts"=> [
              [
                "typeCode"=> "shipper",
                "number"=> "525265227"
              ]
            ],
            "specialInstructions"=> [
              [
                "value"=> "please ring front desk",
                "typeCode"=> "TBD"
              ]
            ],
            "remark"=> "two parcels required pickup",
            "customerDetails"=> [
                "shipperDetails"=>[
                    "postalAddress"=> [
                        "postalCode"=> "1229",
                        "cityName"=> "Dhaka",
                        "countryCode"=> "BD",
                        "addressLine1"=> "House 19, Road 3, Sector 3",
                        "addressLine2"=> "Uttara, Dkaka",
                    ],
                    "contactInformation"=> [
                        "email"=> "info@mbrella.ltd",
                        "phone"=> "01999080846",
                        "mobilePhone"=> "01999080846",
                        "companyName"=> "MBRELLA LTD",
                        "fullName"=> "A Lifestyle Clothing Brand"
                    ],
                ],
                "receiverDetails"=> [
                    "postalAddress"=>[
                        "cityName"=> $order->ship_district,
                        "countryCode"=> $order->country->short_code,
                        "postalCode"=> $order->ship_postCode,
                        "addressLine1"=> $order->ship_address,
                       
                    ],
                    "contactInformation"=>[
                        "email"=>  $order->ship_email??'no@email.com',
                        "phone"=> $order->ship_phone,
                        "mobilePhone"=> $order->ship_phone,
                        "companyName"=> "Home",
                        "fullName"=> $order->ship_first_name.' '. $order->ship_last_name
                    ],
                ]
            ],
            "shipmentDetails"=> [
              [
                "accounts"=> [
                    [
                      "typeCode"=> "shipper",
                      "number"=> "525265227"
                    ]
                ],
                "packages"=> $packagingData,
                "productCode"=> "P",
                "declaredValue"=> 50,
                "unitOfMeasurement"=> "metric",
                "valueAddedServices"=> [
                    [
                      "serviceCode"=> "II",
                      "value"=> 100,
                      "currency"=> "GBP"
                    ]
                ],
                "isCustomsDeclarable"=> true,
                "declaredValueCurrency"=> "USD"
              ]
            ]
        ];

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'Message-Reference' =>  bin2hex(random_bytes(14)),
            'Message-Reference-Date' =>  $messageReferenceDate,
            'Plugin-Name' => 'MyDHL Plugin', // Add plugin name if applicable
            'Plugin-Version' => '1.0', // Add plugin version if applicable
            'Shipping-System-Platform-Name' => 'laravel', // Add platform name if applicable
            'Shipping-System-Platform-Version' => '8.8', // Add platform version if applicable
            'Webstore-Platform-Name' => 'CustomWebstore', // Add webstore platform name if applicable
            'Webstore-Platform-Version' => '1.0', // Add webstore platform version if applicable
            'Authorization' => 'Basic YXBSMnVMNnJGM2FNN3Q6TV4wZFUjMHdXITlvVCMzaw==',
            'Content-Type' => 'application/json',
            'Cookie' => 'BIGipServer~WSB~pl_wsb-express-cbj.dhl.com_443=494676167.64288.0000',
        ])->post($endpoint,$data);
        
        // Check the response
        
        $returnData = json_decode($response, true);
        if($returnData['dispatchConfirmationNumbers'][0]){
            Dhl_shipment::where('order_id', $order->id)
            ->whereNotNull('trackingNumber')
            ->update(['dispatchConfirmationNumbers' => $returnData['dispatchConfirmationNumbers'][0]]);
            dd('Pickup request successfully created!', $returnData['dispatchConfirmationNumbers'][0]);
        }
       


    }



    private function calculateBoxes($totalWeight, $smallBoxCapacity, $largeBoxCapacity, $smallBoxDimensions, $largeBoxDimensions) {
        $smallBoxCount = 0;
        $largeBoxCount = 0;

        // Calculate the number of large boxes needed
        $largeBoxCount = intdiv($totalWeight, $largeBoxCapacity);  // Full large boxes
        $remainingWeight = $totalWeight - ($largeBoxCount * $largeBoxCapacity); // Remaining weight after large boxes

        // Calculate the number of small boxes needed for the remaining weight
        if ($remainingWeight > 0) {
            $smallBoxCount = ceil($remainingWeight / $smallBoxCapacity);
        }

        // Prepare packaging data
        $packagingData = [];

        // Add large boxes to packaging data
        for ($i = 0; $i < $largeBoxCount; $i++) {
            $packagingData[] = [
                "typeCode" => "2BP",
                "weight" => $largeBoxCapacity,
                "dimensions" => $largeBoxDimensions,
                "customerReferences" => [
                    [
                        "value" => "3654673",
                        "typeCode" => "CU"
                    ]
                ],
                "description" => "The box hold ".$smallBoxCount.' items',
                "labelDescription" => "Large box label"
            ];
        }

        // Add small boxes to packaging data
        if ($remainingWeight > 0) {
            $smallBoxCount = ceil($remainingWeight / $smallBoxCapacity);
            for ($i = 0; $i < $smallBoxCount; $i++) {
                $packagingData[] = [
                    "typeCode" => '2BP',
                    "weight" => $smallBoxCapacity,
                    "dimensions" => $smallBoxDimensions,
                    "customerReferences" => [
                        [
                            "value" => "3654673",
                            "typeCode" => "CU"
                        ]
                    ],
                    "description" => "The box hold ".$smallBoxCount.' items',
                    "labelDescription" => "Small box label"
                ];
            }
        }

        // Return packaging data
        return $packagingData;
    }

    private function calculateBoxesForPickup($totalWeight, $smallBoxCapacity, $largeBoxCapacity, $smallBoxDimensions, $largeBoxDimensions) {
        $smallBoxCount = 0;
        $largeBoxCount = 0;

        // Calculate the number of large boxes needed
        $largeBoxCount = intdiv($totalWeight, $largeBoxCapacity);  // Full large boxes
        $remainingWeight = $totalWeight - ($largeBoxCount * $largeBoxCapacity); // Remaining weight after large boxes

        // Calculate the number of small boxes needed for the remaining weight
        if ($remainingWeight > 0) {
            $smallBoxCount = ceil($remainingWeight / $smallBoxCapacity);
        }

        // Prepare packaging data
        $packagingData = [];

        // Add large boxes to packaging data
        for ($i = 0; $i < $largeBoxCount; $i++) {
            $packagingData[] = [
                // "typeCode" => "2BP",
                "weight" => $largeBoxCapacity,
                "dimensions" => $largeBoxDimensions,
            ];
        }

        // Add small boxes to packaging data
        if ($remainingWeight > 0) {
            $smallBoxCount = ceil($remainingWeight / $smallBoxCapacity);
            for ($i = 0; $i < $smallBoxCount; $i++) {
                $packagingData[] = [
                    // "typeCode" => '2BP',
                    "weight" => $smallBoxCapacity,
                    "dimensions" => $smallBoxDimensions,
                ];
            }
        }

        // Return packaging data
        return $packagingData;
    }


}