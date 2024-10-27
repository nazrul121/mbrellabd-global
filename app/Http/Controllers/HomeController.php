<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\City;
use App\Models\City_zone;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\District;
use App\Models\Division;
use App\Models\Faq;
use App\Models\Group;
use App\Models\Highlight;
use App\Models\Inner_group;
use App\Models\Page_post;
use App\Models\Page_post_type;
use App\Models\Policy;
use App\Models\Policy_type;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Show_room;
use App\Models\Slider;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;

use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

use App\Models\Setting;
use App\Models\User;
use App\Models\User_type;
use App\Models\Video;
use App\Models\Order;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\GPSMS;
use App\Services\DHL;
use DateTime;
class HomeController extends Controller
{

    public function __construct(){
        // $this->middleware('auth');
        session()->forget('session_id');
    }

	function test(){
		//dd('hi test');
		return redirect('/home');
	}


    public function index(Request $request, $lang=null){
	 if ($request->is('/') && session('user_currency')->short_name !='BGD') {
             return redirect('/'.strtolower(session('user_currency')->short_name));
        }
	


        // $this->updateUSA();
        // $this->updateAustralia();
        // $this->updateCanada();
        // $this->updateSaudi();
        // $this->updateUEA();
        // $this->updateUK();
        // $this->reArrange_orders();

        
        // $dhl_method = new DHL();
        // $order = Order::where('id','10700')->first();
        // $dhl_shipment =  json_encode($dhl_method->create_shipment($order));


        // $this->set_customer_phone();
        // dd(session('user_currency'));
        $sliders = Cache::remember('sliders', 30, function() {
            $ids = \App\Models\Country_slider::where('country_id', session('user_currency')->id)->select('slider_id')->distinct()->get()->pluck('slider_id')->toArray();
            return Slider::whereIn('id',$ids)->where('status', '1')->select(['link', 'photo'])->orderBy('sort_by')->get();
        });
        

        // $ids = \App\Models\Country_slider::where('country_id',session('user_currency')->id)->select('slider_id')->distinct()->get()->toArray();
        // $sliders = Slider::whereIn('id',$ids)->where('status','1')->select(['link','photo'])->orderBy('sort_by')->get();
       
       
        // ->whereDate('end_date', '<=', (new DateTime)->format('Y-m-d'))

        $promoIds = \App\Models\Country_promotion::where('country_id',session('user_currency')->id)->select('promotion_id')->distinct()->get()->toArray();
        $promotions = Promotion::whereIn('id',$promoIds)->where('status','1')->select(['photo','slug','text_color','bg_color','title'])->get();

        return view('home',compact('sliders','promotions'));
    }

    function updateUSA(){

        $us_locations = [
            'Northeast' => [
                'Connecticut' => [
                    'Bridgeport', 'New Haven', 'Stamford', 'Hartford', 'Waterbury', 'Norwalk', 'Danbury', 'New Britain', 'West Hartford', 'Bristol'
                ],
                'Maine' => [
                    'Portland', 'Lewiston', 'Bangor', 'South Portland', 'Auburn', 'Biddeford', 'Sanford', 'Saco', 'Westbrook', 'Augusta'
                ],
                'Massachusetts' => [
                    'Boston', 'Worcester', 'Springfield', 'Lowell', 'Cambridge', 'New Bedford', 'Brockton', 'Quincy', 'Lynn', 'Fall River'
                ],
                'New Hampshire' => [
                    'Manchester', 'Nashua', 'Concord', 'Derry', 'Rochester', 'Salem', 'Merrimack', 'Hudson', 'Londonderry', 'Keene'
                ],
                'New Jersey' => [
                    'Newark', 'Jersey City', 'Paterson', 'Elizabeth', 'Lakewood', 'Edison', 'Woodbridge', 'Toms River', 'Hamilton', 'Trenton'
                ],
                'New York' => [
                    'New York City', 'Buffalo', 'Rochester', 'Yonkers', 'Syracuse', 'Albany', 'New Rochelle', 'Mount Vernon', 'Schenectady', 'Utica'
                ],
                'Pennsylvania' => [
                    'Philadelphia', 'Pittsburgh', 'Allentown', 'Erie', 'Reading', 'Scranton', 'Bethlehem', 'Lancaster', 'Harrisburg', 'York'
                ],
                'Rhode Island' => [
                    'Providence', 'Cranston', 'Warwick', 'Pawtucket', 'East Providence', 'Woonsocket', 'Coventry', 'Cumberland', 'North Providence', 'South Kingstown'
                ],
                'Vermont' => [
                    'Burlington', 'South Burlington', 'Rutland', 'Essex Junction', 'Bennington', 'Barre', 'Middlebury', 'St. Albans', 'Brattleboro', 'Montpelier'
                ]
            ],
            'Midwest' => [
                'Illinois' => [
                    'Chicago', 'Aurora', 'Naperville', 'Joliet', 'Rockford', 'Springfield', 'Peoria', 'Elgin', 'Waukegan', 'Cicero'
                ],
                'Indiana' => [
                    'Indianapolis', 'Fort Wayne', 'Evansville', 'South Bend', 'Carmel', 'Fishers', 'Bloomington', 'Hammond', 'Gary', 'Muncie'
                ],
                'Iowa' => [
                    'Des Moines', 'Cedar Rapids', 'Davenport', 'Sioux City', 'Waterloo', 'Iowa City', 'Council Bluffs', 'Ames', 'Dubuque', 'Ankeny'
                ],
                'Kansas' => [
                    'Wichita', 'Overland Park', 'Kansas City', 'Olathe', 'Topeka', 'Lawrence', 'Shawnee', 'Manhattan', 'Lenexa', 'Salina'
                ],
                'Michigan' => [
                    'Detroit', 'Grand Rapids', 'Warren', 'Sterling Heights', 'Ann Arbor', 'Lansing', 'Flint', 'Dearborn', 'Livonia', 'Westland'
                ],
                'Minnesota' => [
                    'Minneapolis', 'Saint Paul', 'Rochester', 'Duluth', 'Bloomington', 'Brooklyn Park', 'Plymouth', 'Maple Grove', 'Woodbury', 'Eagan'
                ],
                'Missouri' => [
                    'Kansas City', 'St. Louis', 'Springfield', 'Independence', 'Columbia', 'Lee\'s Summit', 'O\'Fallon', 'St. Joseph', 'St. Charles', 'Blue Springs'
                ],
                'Nebraska' => [
                    'Omaha', 'Lincoln', 'Bellevue', 'Grand Island', 'Kearney', 'Fremont', 'Hastings', 'North Platte', 'Norfolk', 'Columbus'
                ],
                'North Dakota' => [
                    'Fargo', 'Bismarck', 'Grand Forks', 'Minot', 'West Fargo', 'Williston', 'Dickinson', 'Mandan', 'Jamestown', 'Valley City'
                ],
                'Ohio' => [
                    'Columbus', 'Cleveland', 'Cincinnati', 'Toledo', 'Akron', 'Dayton', 'Parma', 'Canton', 'Youngstown', 'Lorain'
                ],
                'South Dakota' => [
                    'Sioux Falls', 'Rapid City', 'Aberdeen', 'Brookings', 'Watertown', 'Mitchell', 'Yankton', 'Pierre', 'Huron', 'Vermillion'
                ],
                'Wisconsin' => [
                    'Milwaukee', 'Madison', 'Green Bay', 'Kenosha', 'Racine', 'Appleton', 'Waukesha', 'Oshkosh', 'Eau Claire', 'Janesville'
                ]
            ],
            'South' => [
                'Alabama' => [
                    'Birmingham', 'Montgomery', 'Huntsville', 'Mobile', 'Tuscaloosa', 'Hoover', 'Dothan', 'Auburn', 'Decatur', 'Madison'
                ],
                'Arkansas' => [
                    'Little Rock', 'Fort Smith', 'Fayetteville', 'Springdale', 'Jonesboro', 'North Little Rock', 'Conway', 'Rogers', 'Bentonville', 'Pine Bluff'
                ],
                'Florida' => [
                    'Jacksonville', 'Miami', 'Tampa', 'Orlando', 'St. Petersburg', 'Hialeah', 'Tallahassee', 'Fort Lauderdale', 'Port St. Lucie', 'Cape Coral'
                ],
                'Georgia' => [
                    'Atlanta', 'Augusta', 'Columbus', 'Macon', 'Savannah', 'Athens', 'Sandy Springs', 'Roswell', 'Johns Creek', 'Albany'
                ],
                'Kentucky' => [
                    'Louisville', 'Lexington', 'Bowling Green', 'Owensboro', 'Covington', 'Richmond', 'Florence', 'Georgetown', 'Hopkinsville', 'Nicholasville'
                ],
                'Louisiana' => [
                    'New Orleans', 'Baton Rouge', 'Shreveport', 'Lafayette', 'Lake Charles', 'Kenner', 'Bossier City', 'Monroe', 'Alexandria', 'Houma'
                ],
                'Maryland' => [
                    'Baltimore', 'Frederick', 'Rockville', 'Gaithersburg', 'Bowie', 'Hagerstown', 'Annapolis', 'Salisbury', 'College Park', 'Laurel'
                ],
                'Mississippi' => [
                    'Jackson', 'Gulfport', 'Southaven', 'Biloxi', 'Hattiesburg', 'Olive Branch', 'Tupelo', 'Meridian', 'Greenville', 'Horn Lake'
                ],
                'North Carolina' => [
                    'Charlotte', 'Raleigh', 'Greensboro', 'Durham', 'Winston-Salem', 'Fayetteville', 'Cary', 'Wilmington', 'High Point', 'Concord'
                ],
                'South Carolina' => [
                    'Charleston', 'Columbia', 'North Charleston', 'Mount Pleasant', 'Rock Hill', 'Greenville', 'Summerville', 'Sumter', 'Hilton Head Island', 'Florence'
                ],
                'Texas' => [
                    'Houston', 'San Antonio', 'Dallas', 'Austin', 'Fort Worth', 'El Paso', 'Arlington', 'Corpus Christi', 'Plano', 'Laredo'
                ],
                'Virginia' => [
                    'Virginia Beach', 'Norfolk', 'Chesapeake', 'Richmond', 'Newport News', 'Alexandria', 'Hampton', 'Roanoke', 'Portsmouth', 'Suffolk'
                ],
                'West Virginia' => [
                    'Charleston', 'Huntington', 'Morgantown', 'Parkersburg', 'Wheeling', 'Weirton', 'Fairmont', 'Beckley', 'Martinsburg', 'Clarksburg'
                ]
            ],
            'West' => [
                'Alaska' => [
                    'Anchorage', 'Fairbanks', 'Juneau', 'Sitka', 'Ketchikan', 'Wasilla', 'Kenai', 'Kodiak', 'Bethel', 'Palmer'
                ],
                'Arizona' => [
                    'Phoenix', 'Tucson', 'Mesa', 'Chandler', 'Glendale', 'Scottsdale', 'Gilbert', 'Tempe', 'Peoria', 'Surprise'
                ],
                'California' => [
                    'Los Angeles', 'San Diego', 'San Jose', 'San Francisco', 'Fresno', 'Sacramento', 'Long Beach', 'Oakland', 'Bakersfield', 'Anaheim'
                ],
                'Colorado' => [
                    'Denver', 'Colorado Springs', 'Aurora', 'Fort Collins', 'Lakewood', 'Thornton', 'Arvada', 'Westminster', 'Pueblo', 'Centennial'
                ],
                'Hawaii' => [
                    'Honolulu', 'Hilo', 'Kailua', 'Kapolei', 'Waipahu', 'Pearl City', 'Mililani', 'Ewa Beach', 'Kahului', 'Wahiawa'
                ],
                'Idaho' => [
                    'Boise', 'Meridian', 'Nampa', 'Idaho Falls', 'Caldwell', 'Pocatello', 'Coeur d\'Alene', 'Twin Falls', 'Lewiston', 'Post Falls'
                ],
                'Montana' => [
                    'Billings', 'Missoula', 'Great Falls', 'Bozeman', 'Butte', 'Helena', 'Kalispell', 'Havre', 'Belgrade', 'Miles City'
                ],
                'Nevada' => [
                    'Las Vegas', 'Henderson', 'Reno', 'North Las Vegas', 'Sparks', 'Carson City', 'Fernley', 'Elko', 'Mesquite', 'Boulder City'
                ],
                'Oregon' => [
                    'Portland', 'Eugene', 'Salem', 'Gresham', 'Hillsboro', 'Beaverton', 'Bend', 'Medford', 'Springfield', 'Corvallis'
                ],
                'Utah' => [
                    'Salt Lake City', 'West Valley City', 'Provo', 'West Jordan', 'Orem', 'Sandy', 'Ogden', 'St. George', 'Layton', 'South Jordan'
                ],
                'Washington' => [
                    'Seattle', 'Spokane', 'Tacoma', 'Vancouver', 'Bellevue', 'Kent', 'Everett', 'Renton', 'Yakima', 'Bellingham'
                ],
                'Wyoming' => [
                    'Cheyenne', 'Casper', 'Laramie', 'Gillette', 'Rock Springs', 'Sheridan', 'Green River', 'Evanston', 'Riverton', 'Cody'
                ]
            ]
        ];

        foreach ($us_locations as $reg=>$regions) {
            $checkDiv = Division::where(['country_id'=>'1', 'name'=>$reg]);
            if($checkDiv->count() <1){
                $divison = Division::create(['country_id'=>'1', 'name'=>$reg]);
            }
            else $divison = $checkDiv->first();
            echo $reg.':<br/><hr/>';
           
            foreach($regions as $state=>$cities){
                $checkDis = District::where(['division_id'=>$divison->id, 'name'=>$state]);
                if($checkDis->count() <1){
                    $district = District::create(['division_id'=>$divison->id, 'name'=>$state]);
                }
                else $district = $checkDis->first();

                echo '&nbsp; -'.$state.'<br/>';
                foreach($cities as $city){
                    $checkCity = City::where(['district_id'=>$district->id, 'name'=>$city]);
                    if($checkCity->count() <1){
                        City::create(['district_id'=>$district->id, 'name'=>$city]);
                    }
                    echo '&nbsp; &nbsp; -'.$city.'<br/>';
                }
            }
           
            echo '<br/></hr><br/>';
           
        }     
    }

    function updateAustralia(){

        $australiaLocations = [
            'Regions' => [
                'Eastern Region' => [
                    'States' => [
                        'New South Wales' => [
                            'Cities' => [
                                'Sydney',
                                'Newcastle',
                                'Wollongong',
                                'Central Coast',
                                'Coffs Harbour',
                                'Port Macquarie',
                                'Maitland',
                                'Wagga Wagga',
                                'Tamworth',
                                'Lismore',
                                'Bathurst',
                                'Dubbo',
                                'Goulburn',
                                'Armidale',
                                'Tweed Heads',
                                'Blue Mountains',
                                'Queanbeyan',
                            ],
                        ],
                        'Victoria' => [
                            'Cities' => [
                                'Melbourne',
                                'Geelong',
                                'Ballarat',
                                'Bendigo',
                                'Shepparton',
                                'Wangaratta',
                                'Mildura',
                                'Wodonga',
                                'Frankston',
                                'Melton',
                                'Sunbury',
                                'Traralgon',
                                'Dandenong',
                                'Horsham',
                                'Bairnsdale',
                                'Colac',
                            ],
                        ],
                        'Queensland' => [
                            'Cities' => [
                                'Brisbane',
                                'Gold Coast',
                                'Cairns',
                                'Townsville',
                                'Mackay',
                                'Bundaberg',
                                'Toowoomba',
                                'Rockhampton',
                                'Hervey Bay',
                                'Gladstone',
                                'Ipswich',
                                'Maroochydore',
                                'Maryborough',
                                'Gympie',
                                'Redcliffe',
                                'Noosa Heads',
                            ],
                        ],
                    ],
                ],
                'Southern Region' => [
                    'States' => [
                        'South Australia' => [
                            'Cities' => [
                                'Adelaide',
                                'Mount Gambier',
                                'Whyalla',
                                'Port Adelaide',
                                'Victor Harbor',
                                'Murray Bridge',
                                'Gawler',
                                'Port Lincoln',
                                'Whyalla',
                                'Mount Barker',
                                'Ceduna',
                                'Barmera',
                                'Port Pirie',
                            ],
                        ],
                        'Tasmania' => [
                            'Cities' => [
                                'Hobart',
                                'Launceston',
                                'Devonport',
                                'Burnie',
                                'Kingston',
                                'Bicheno',
                                'Sorell',
                                'Latrobe',
                                'Clarence',
                                'Queenstown',
                            ],
                        ],
                    ],
                ],
                'Western Region' => [
                    'States' => [
                        'Western Australia' => [
                            'Cities' => [
                                'Perth',
                                'Bunbury',
                                'Mandurah',
                                'Kalgoorlie',
                                'Geraldton',
                                'Albany',
                                'Karratha',
                                'Broome',
                                'Port Hedland',
                                'Esperance',
                                'Northam',
                                'Armadale',
                                'Rockingham',
                                'Bayswater',
                            ],
                        ],
                    ],
                ],
                'Northern Region' => [
                    'States' => [
                        'Northern Territory' => [
                            'Cities' => [
                                'Darwin',
                                'Alice Springs',
                                'Katherine',
                                'Palmerston',
                                'Nhulunbuy',
                                'Yulara',
                                'Tennant Creek',
                                'Casuarina',
                                'Gove',
                            ],
                        ],
                    ],
                ],
                'Capital Region' => [
                    'States' => [
                        'Australian Capital Territory' => [
                            'Cities' => [
                                'Canberra',
                                'Queanbeyan',
                                'Gungahlin',
                                'Belconnen',
                                'Tuggeranong',
                                'Woden',
                            ],
                        ],
                    ],
                ],
                'Other Regions' => [
                    'States' => [
                        'Victoria' => [
                            'Cities' => [
                                'Bendigo',
                                'Ballarat',
                                'Geelong',
                                'Latrobe Valley',
                            ],
                        ],
                        'Queensland' => [
                            'Cities' => [
                                'Sunshine Coast',
                                'Wide Bay-Burnett',
                            ],
                        ],
                        'New South Wales' => [
                            'Cities' => [
                                'Central Coast',
                                'Illawarra',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        
        // Displaying the structured locations
        foreach ($australiaLocations['Regions'] as $region => $details) {
            $checkDiv = Division::where(['country_id'=>'3', 'name'=>$region]);
            if($checkDiv->count() <1){
                $divison = Division::create(['country_id'=>'3', 'name'=>$region]);
            }
            else $divison = $checkDiv->first();

            foreach ($details['States'] as $state => $citiesDetails) {
                $checkDis = District::where(['division_id'=>$divison->id, 'name'=>$state]);
                if($checkDis->count() <1){
                    $district = District::create(['division_id'=>$divison->id, 'name'=>$state]);
                }
                else $district = $checkDis->first();
           
                foreach ($citiesDetails['Cities'] as $city) {
                    $checkCity = City::where(['district_id'=>$district->id, 'name'=>$city]);
                    if($checkCity->count() <1){
                        City::create(['district_id'=>$district->id, 'name'=>$city]);
                    }
                }
               
            } 
        }


    }

    function updateCanada(){
        $canada_locations = [
            'British Columbia' => [
                'states' => [
                    'Metro Vancouver' => [
                        'Vancouver', 'Burnaby', 'Richmond', 'Surrey', 'Coquitlam', 'Langley', 'Delta', 'Maple Ridge', 'Abbotsford', 'White Rock', 
                        'North Vancouver', 'West Vancouver', 'Pitt Meadows', 'Port Moody', 'New Westminster', 'Port Coquitlam'
                    ],
                    'Vancouver Island' => [
                        'Victoria', 'Nanaimo', 'Courtenay', 'Comox', 'Parksville', 'Qualicum Beach', 'Duncan', 'Campbell River', 'Sidney', 
                        'Esquimalt', 'Sooke'
                    ],
                    'Thompson-Okanagan' => [
                        'Kelowna', 'Penticton', 'Vernon', 'Kamloops', 'Summerland', 'Oliver', 'Enderby', 'Salmon Arm', 'Naramata', 'Lake Country'
                    ],
                    'Kootenay' => [
                        'Cranbrook', 'Fernie', 'Invermere', 'Kimberley', 'Golden', 'Castlegar', 'Nelson', 'Columbia Falls', 'Radium Hot Springs'
                    ],
                    'Northern British Columbia' => [
                        'Prince George', 'Fort St. John', 'Dawson Creek', 'Smithers', 'Terrace', 'Kitimat'
                    ]
                ]
            ],
            'Alberta' => [
                'states' => [
                    'Calgary Region' => [
                        'Calgary', 'Airdrie', 'Okotoks', 'Cochrane', 'Chestermere', 'Strathmore'
                    ],
                    'Edmonton Region' => [
                        'Edmonton', 'St. Albert', 'Sherwood Park', 'Leduc', 'Fort Saskatchewan', 'Spruce Grove', 'Beaumont'
                    ],
                    'Red Deer Region' => [
                        'Red Deer', 'Sylvan Lake', 'Innisfail', 'Blackfalds', 'Lacombe'
                    ],
                    'Lethbridge Region' => [
                        'Lethbridge', 'Coaldale', 'Taber', 'Fort Macleod', 'Picture Butte'
                    ],
                    'Medicine Hat Region' => [
                        'Medicine Hat', 'Bow Island', 'Redcliff', 'Dunmore'
                    ]
                ]
            ],
            'Saskatchewan' => [
                'states' => [
                    'Saskatoon' => [
                        'Saskatoon', 'Warman', 'Martensville', 'Osler', 'Humboldt', 'Moose Jaw'
                    ],
                    'Regina' => [
                        'Regina', 'Moose Jaw', 'Yorkton', 'Estevan', 'Swift Current', 'Prince Albert'
                    ]
                ]
            ],
            'Manitoba' => [
                'states' => [
                    'Winnipeg' => [
                        'Winnipeg', 'Brandon', 'Steinbach', 'Selkirk', 'Portage la Prairie'
                    ],
                    'Thompson' => [
                        'Thompson', 'Flin Flon', 'The Pas', 'Snow Lake'
                    ],
                    'Interlake' => [
                        'Selkirk', 'Gimli', 'Stonewall', 'Teulon', 'Winkler'
                    ]
                ]
            ],
            'Ontario' => [
                'states' => [
                    'Toronto' => [
                        'Toronto', 'Mississauga', 'Brampton', 'Markham', 'Vaughan', 'Oakville', 'Richmond Hill', 'Burlington', 'Ajax', 'Whitby'
                    ],
                    'Ottawa' => [
                        'Ottawa', 'Kanata', 'Nepean', 'Orleans', 'Barrhaven', 'Rockland'
                    ],
                    'Hamilton' => [
                        'Hamilton', 'Stoney Creek', 'Dundas', 'Ancaster', 'Grimsby'
                    ],
                    'London' => [
                        'London', 'St. Thomas', 'Stratford', 'Woodstock', 'Ingersoll'
                    ],
                    'Kitchener-Waterloo' => [
                        'Kitchener', 'Waterloo', 'Cambridge', 'Guelph', 'Stratford'
                    ]
                ]
            ],
            'Quebec' => [
                'states' => [
                    'Montreal' => [
                        'Montreal', 'Laval', 'Longueuil', 'Repentigny', 'Terrebonne'
                    ],
                    'Quebec City' => [
                        'Quebec City', 'Limoilou', 'Beauport', 'Charlesbourg', 'Sillery'
                    ],
                    'Gatineau' => [
                        'Gatineau', 'Aylmer', 'Hull', 'Masson-Angers'
                    ]
                ]
            ],
            'New Brunswick' => [
                'states' => [
                    'Fredericton' => [
                        'Fredericton', 'Saint John', 'Moncton', 'Dieppe', 'Riverview', 'Quispamsis', 'Oromocto'
                    ]
                ]
            ],
            'Nova Scotia' => [
                'states' => [
                    'Halifax' => [
                        'Halifax', 'Dartmouth', 'Sackville', 'New Glasgow', 'Truro', 'Yarmouth', 'Bridgewater', 'Antigonish'
                    ]
                ]
            ],
            'Prince Edward Island' => [
                'states' => [
                    'Charlottetown' => [
                        'Charlottetown', 'Summerside', 'Stratford', 'Cornwall'
                    ]
                ]
            ],
            'Newfoundland and Labrador' => [
                'states' => [
                    'St. John\'s' => [
                        'St. John\'s', 'Mount Pearl', 'Corner Brook', 'Gander', 'Grand Falls-Windsor', 'Bay Roberts', 'Labrador City', 'Happy Valley-Goose Bay'
                    ]
                ]
            ],
            'Yukon' => [
                'states' => [
                    'Whitehorse' => [
                        'Whitehorse', 'Dawson City', 'Watson Lake', 'Haines Junction'
                    ]
                ]
            ],
            'Northwest Territories' => [
                'states' => [
                    'Yellowknife' => [
                        'Yellowknife', 'Hay River', 'Inuvik', 'Fort Smith', 'Norman Wells', 'Behchoko'
                    ]
                ]
            ],
            'Nunavut' => [
                'states' => [
                    'Iqaluit' => [
                        'Iqaluit', 'Rankin Inlet', 'Arviat', 'Baker Lake', 'Cambridge Bay', 'Pangnirtung', 'Cape Dorset', 'Gjoa Haven'
                    ]
                ]
            ],
        ];

        foreach ($canada_locations as $reg=>$regions) {
            $checkDiv = Division::where(['country_id'=>'4', 'name'=>$reg]);
            if($checkDiv->count() <1){
                $divison = Division::create(['country_id'=>'4', 'name'=>$reg]);
            }
            else $divison = $checkDiv->first();
        
            echo $reg.':<br/><hr/>';
            // dd( $reg, $regions['states']);
           
            foreach($regions['states'] as $state=>$cities){
                $checkDis = District::where(['division_id'=>$divison->id, 'name'=>$state]);
                if($checkDis->count() <1){
                    $district = District::create(['division_id'=>$divison->id, 'name'=>$state]);
                }
                else $district = $checkDis->first();

                echo '&nbsp; -'.$state.'<br/>';
                foreach($cities as $city){
                    $checkCity = City::where(['district_id'=>$district->id, 'name'=>$city]);
                    if($checkCity->count() <1){
                        City::create(['district_id'=>$district->id, 'name'=>$city]);
                    }
                    echo '&nbsp; &nbsp; -'.$city.'<br/>';
                }
            }
            echo '<br/></hr><br/>';
        }
    }

    function updateSaudi(){
        $saudi_arabia_locations = [
            'Riyadh Region' => [
                'provinces' => [
                    'Riyadh' => [
                        'Riyadh', 'Al Kharj', 'Al Majma\'ah', 'Dawadmi', 'Al Quwayiyah', 'Al Sulayyil', 'Al Muzahimiyah', 'Al Khobar', 'Diriyah',
                        'Al Ghat', 'Al Dhurma', 'Thadiq', 'Al Hamra', 'Al Wadia', 'Al Olya', 'Al Aflaj'
                    ]
                ]
            ],
            'Makkah Region' => [
                'provinces' => [
                    'Makkah' => [
                        'Makkah', 'Jeddah', 'Taif', 'Khulais', 'Al Jumum', 'Al Laith', 'Mina', 'Arafat', 'Rabigh', 'Adham', 'Al Qunfudhah'
                    ],
                    'Al Bahah' => [
                        'Al Bahah', 'Baljurashi', 'Qilwah', 'Al Makhwah', 'Bani Hasan', 'Al Aqiq', 'Al Mandak'
                    ]
                ]
            ],
            'Eastern Province' => [
                'provinces' => [
                    'Dammam' => [
                        'Dammam', 'Khobar', 'Dhahran', 'Qatif', 'Al Ahsa', 'Safwa', 'Al Khobar', 'Jubail', 'Al Hasa', 'Khobar', 'Al Qatif', 'Abqaiq'
                    ],
                    'Al Ahsa' => [
                        'Al Ahsa', 'Al Hofuf', 'Al Mubarraz', 'Al Khobar', 'Al Qarah', 'Mubarraz'
                    ]
                ]
            ],
            'Al Qassim Region' => [
                'provinces' => [
                    'Buraidah' => [
                        'Buraidah', 'Unaizah', 'Al Rass', 'Al Mithnab', 'Al Khabra', 'Al Mikhwah'
                    ]
                ]
            ],
            'Asir Region' => [
                'provinces' => [
                    'Abha' => [
                        'Abha', 'Khamis Mushait', 'Tarnim', 'Mahd Al Dhahab', 'Al Namas', 'Al Soudah', 'Dhahran Al Janub'
                    ]
                ]
            ],
            'Hail Region' => [
                'provinces' => [
                    'Hail' => [
                        'Hail', 'Al Mikhwah', 'Al Ula', 'Al Ghabra', 'Samta', 'Al Samhan'
                    ]
                ]
            ],
            'Tabuk Region' => [
                'provinces' => [
                    'Tabuk' => [
                        'Tabuk', 'Duba', 'Al Wajh', 'Tayma', 'Alaqil', 'Umluj', 'Khalidiyah', 'Al Khuraybah'
                    ]
                ]
            ],
            'Northern Borders Region' => [
                'provinces' => [
                    'Arar' => [
                        'Arar', 'Turaif', 'Al-Yaum', 'Rafha', 'Al Qurrayat', 'Al Uqayr'
                    ]
                ]
            ],
            'Jizan Region' => [
                'provinces' => [
                    'Jizan' => [
                        'Jizan', 'Abu Arish', 'Sabya', 'Al-Darb', 'Farasan', 'Al Khobar', 'Al Duwailah', 'Al Ahd'
                    ]
                ]
            ],
            'Najran Region' => [
                'provinces' => [
                    'Najran' => [
                        'Najran', 'Sharurah', 'Habouna', 'Thar', 'Al-Ukhdood', 'Al-Aflaj'
                    ]
                ]
            ],
            'Al Madinah Region' => [
                'provinces' => [
                    'Madinah' => [
                        'Madinah', 'Yanbu', 'Al Ula', 'Khaybar', 'Badr', 'Al Hujun', 'Al Huta'
                    ]
                ]
            ],
            'Al Jawf Region' => [
                'provinces' => [
                    'Sakaka' => [
                        'Sakaka', 'Al Qurayyat', 'Dumat Al Jandal', 'Abu Samra', 'Al Hada'
                    ]
                ]
            ],
        ];

        foreach ($saudi_arabia_locations as $reg=>$regions) {
            $checkDiv = Division::where(['country_id'=>'5', 'name'=>$reg]);
            if($checkDiv->count() <1){
                $divison = Division::create(['country_id'=>'5', 'name'=>$reg]);
            }
            else $divison = $checkDiv->first();
        
            echo $reg.':<br/><hr/>';
            // dd( $reg, $regions['provinces']);
           
            foreach($regions['provinces'] as $state=>$cities){
                $checkDis = District::where(['division_id'=>$divison->id, 'name'=>$state]);
                if($checkDis->count() <1){
                    $district = District::create(['division_id'=>$divison->id, 'name'=>$state]);
                }
                else $district = $checkDis->first();

                echo '&nbsp; -'.$state.'<br/>';
                foreach($cities as $city){
                    $checkCity = City::where(['district_id'=>$district->id, 'name'=>$city]);
                    if($checkCity->count() <1){
                        City::create(['district_id'=>$district->id, 'name'=>$city]);
                    }
                    echo '&nbsp; &nbsp; -'.$city.'<br/>';
                }
            }
            echo '<br/></hr><br/>';
        }
        
    }

    function updateUEA(){
        $uae_locations = [
            'Abu Dhabi' => [
                'states' => [
                    'Abu Dhabi' => [
                        'cities' => [
                            'Abu Dhabi', 'Al Ain', 'Al Dhafra', 'Liwa', 
                            'Madinat Zayed', 'Suweihan', 'Al Wathba', 
                            'Al Reem Island', 'Al Maryah Island', 'Saadiyat Island', 
                            'Bani Yas', 'Mirfa', 'Al Falah', 
                            'Al Shamkha', 'Al Rumaithiya', 'Al Bateen', 
                            'Mohammed Bin Zayed City', 'Western Region'
                        ]
                    ],
                ]
            ],
            'Dubai' => [
                'states' => [
                    'Dubai' => [
                        'cities' => [
                            'Dubai', 'Deira', 'Bur Dubai', 'Jumeirah', 
                            'Dubai Marina', 'Downtown Dubai', 'Business Bay', 
                            'Al Quoz', 'Mirdif', 'Al Barsha', 
                            'Jebel Ali', 'Arabian Ranches', 'Dubai Silicon Oasis', 
                            'Emirates Hills', 'Dubai Sports City', 'Al Furjan', 
                            'Palm Jumeirah', 'Al Rashidiya', 'Al Safa', 
                            'Al Jafiliya', 'Al Karama', 'Al Ghusais'
                        ]
                    ],
                ]
            ],
            'Sharjah' => [
                'states' => [
                    'Sharjah' => [
                        'cities' => [
                            'Sharjah', 'Khor Fakkan', 'Kalba', 'Al Dhaid', 
                            'Mleiha', 'Al Hamriyah', 'Al Gulaya', 
                            'Al Qasimia', 'Al Mujarrah', 'Al Rolla', 
                            'Al Hira', 'Al Heerah', 'Al Sharq', 
                            'Al Gulaya', 'Al Tawan', 'Al Saja'
                        ]
                    ],
                ]
            ],
            'Ajman' => [
                'states' => [
                    'Ajman' => [
                        'cities' => [
                            'Ajman', 'Masfut', 'Al Manama', 'Al Nuaimiya', 
                            'Al Jurf', 'Al Rashidiya', 'Al Zahra', 
                            'Al Mowaihat', 'Al Yasmeen', 'Al Zohra'
                        ]
                    ],
                ]
            ],
            'Umm Al-Quwain' => [
                'states' => [
                    'Umm Al-Quwain' => [
                        'cities' => [
                            'Umm Al-Quwain', 'Falaj Al Moalla', 'Al Raas', 
                            'Al Hamriyah', 'Al Muwaij’i', 'Al Jazeera Al Hamra', 
                            'Al Dour', 'Al Salama', 'Al Humaidiya'
                        ]
                    ],
                ]
            ],
            'Fujairah' => [
                'states' => [
                    'Fujairah' => [
                        'cities' => [
                            'Fujairah', 'Khor Fakkan', 'Al Aqah', 
                            'Masafi', 'Dibba Al Fujairah', 'Bidya', 
                            'Al Badiyah', 'Al Hala', 'Madhab', 
                            'Sakamkam', 'Al Tawi', 'Al Dhaid'
                        ]
                    ],
                ]
            ],
            'Ras Al Khaimah' => [
                'states' => [
                    'Ras Al Khaimah' => [
                        'cities' => [
                            'Ras Al Khaimah', 'Al Rams', 'Digdaga', 
                            'Al Jeer', 'Masafi', 'Sham', 
                            'Ghalilah', 'Khor Khwair', 'Barkha', 
                            'Al Maamoura', 'Jazirat Al Hamra', 'Shamal', 
                            'Al Hamra', 'Al Qawasim', 'Al Nakheel'
                        ]
                    ],
                ]
            ],
        ];

        foreach ($uae_locations as $reg=>$regions) {
            $checkDiv = Division::where(['country_id'=>'6', 'name'=>$reg]);
            if($checkDiv->count() <1){
                $divison = Division::create(['country_id'=>'6', 'name'=>$reg]);
            }
            else $divison = $checkDiv->first();
        
            echo $reg.':<br/><hr/>';
           
           
            foreach($regions['states'] as $state=>$cities){
                // dd( $reg, $regions['states'], $cities['cities']);
                $checkDis = District::where(['division_id'=>$divison->id, 'name'=>$state]);
                if($checkDis->count() <1){
                    $district = District::create(['division_id'=>$divison->id, 'name'=>$state]);
                }
                else $district = $checkDis->first();

                echo '&nbsp; -'.$state.'<br/>';
                foreach($cities['cities'] as $city){
                    $checkCity = City::where(['district_id'=>$district->id, 'name'=>$city]);
                    if($checkCity->count() <1){
                        City::create(['district_id'=>$district->id, 'name'=>$city]);
                    }
                    echo '&nbsp; &nbsp; -'.$city.'<br/>';
                }
            }
            echo '<br/></hr><br/>';
        }
        
    }

    function updateUK(){
        $uk_locations = [
            'England' => [
                'counties' => [
                    'Greater London' => [
                        'cities' => [
                            'London', 'Bromley', 'Croydon', 'Harrow', 
                            'Hillingdon', 'Hounslow', 'Kingston upon Thames', 
                            'Lambeth', 'Lewisham', 'Richmond upon Thames', 
                            'Southwark', 'Sutton', 'Tower Hamlets', 'Wandsworth', 
                            'Barking and Dagenham', 'Barnet', 'Bexley', 
                            'Brent', 'Bromley', 'Enfield', 'Greenwich'
                        ]
                    ],
                    'West Midlands' => [
                        'cities' => [
                            'Birmingham', 'Coventry', 'Dudley', 'Wolverhampton', 
                            'Solihull', 'Sandwell', 'Walsall', 'Bromsgrove', 
                            'Stourbridge', 'West Bromwich'
                        ]
                    ],
                    'West Yorkshire' => [
                        'cities' => [
                            'Leeds', 'Bradford', 'Huddersfield', 'Wakefield', 
                            'Halifax', 'Pudsey', 'Batley', 'Dewsbury'
                        ]
                    ],
                    'Merseyside' => [
                        'cities' => [
                            'Liverpool', 'Wirral', 'St Helens', 'Knowsley', 
                            'Southport', 'Bootle', 'Sefton', 'Halton'
                        ]
                    ],
                    'Lancashire' => [
                        'cities' => [
                            'Preston', 'Blackburn', 'Burnley', 'Lancaster', 
                            'Blackpool', 'Ormskirk', 'Darwen', 'Fleetwood'
                        ]
                    ],
                    'Surrey' => [
                        'cities' => [
                            'Guildford', 'Woking', 'Epsom', 'Farnham', 
                            'Reigate', 'Camberley', 'Leatherhead', 
                            'Caterham', 'Godalming', 'Walton-on-Thames'
                        ]
                    ],
                    'Kent' => [
                        'cities' => [
                            'Canterbury', 'Dover', 'Maidstone', 'Rochester', 
                            'Gravesend', 'Tunbridge Wells', 'Dartford', 
                            'Ashford', 'Folkestone', 'Margate'
                        ]
                    ],
                    'Essex' => [
                        'cities' => [
                            'Chelmsford', 'Colchester', 'Basildon', 
                            'Southend-on-Sea', 'Brentwood', 'Braintree', 
                            'Harlow', 'Saffron Walden', 'Barking', 
                            'Dagenham'
                        ]
                    ],
                    'Hampshire' => [
                        'cities' => [
                            'Southampton', 'Portsmouth', 'Winchester', 
                            'Basingstoke', 'Farnborough', 'Andover', 
                            'Eastleigh', 'Havant', 'Fleet', 
                            'Romsey'
                        ]
                    ],
                ]
            ],
            'Scotland' => [
                'counties' => [
                    'Central Scotland' => [
                        'cities' => [
                            'Glasgow', 'Falkirk', 'Stirling', 'Alloa', 
                            'Airdrie', 'Cumbernauld', 'Livingston', 
                            'Linlithgow', 'Wishaw', 'Motherwell'
                        ]
                    ],
                    'Highlands' => [
                        'cities' => [
                            'Inverness', 'Fort William', 'Elgin', 'Dingwall', 
                            'Nairn', 'Thurso', 'Wick', 'Kyle of Lochalsh'
                        ]
                    ],
                    'Southern Scotland' => [
                        'cities' => [
                            'Dumfries', 'Ayr', 'Kilmarnock', 'Cumbernauld', 
                            'Stranraer', 'Moffat', 'Sanquhar', 'Lockerbie'
                        ]
                    ],
                    'North East Scotland' => [
                        'cities' => [
                            'Aberdeen', 'Peterhead', 'Fraserburgh', 
                            'Stonehaven', 'Inverurie', 'Westhill', 
                            'Banchory', 'Ellon'
                        ]
                    ],
                ]
            ],
            'Wales' => [
                'counties' => [
                    'South Wales' => [
                        'cities' => [
                            'Cardiff', 'Swansea', 'Newport', 'Merthyr Tydfil', 
                            'Bridgend', 'Neath', 'Port Talbot', 
                            'Barry', 'Caerphilly', 'Cwmbran'
                        ]
                    ],
                    'North Wales' => [
                        'cities' => [
                            'Wrexham', 'Bangor', 'Llandudno', 'Colwyn Bay', 
                            'Aberystwyth', 'Holyhead', 'Rhyl', 
                            'Conwy', 'Flint', 'Denbigh'
                        ]
                    ],
                    'Mid Wales' => [
                        'cities' => [
                            'Llanidloes', 'Newtown', 'Builth Wells', 
                            'Machynlleth', 'Hay-on-Wye'
                        ]
                    ],
                ]
            ],
            'Northern Ireland' => [
                'counties' => [
                    'Antrim' => [
                        'cities' => [
                            'Belfast', 'Lisburn', 'Newtownabbey', 'Carrickfergus', 
                            'Antrim', 'Ballymena', 'Ballymoney', 
                            'Magherafelt', 'Larne'
                        ]
                    ],
                    'Down' => [
                        'cities' => [
                            'Newry', 'Bangor', 'Holywood', 'Warrenpoint', 
                            'Portaferry', 'Dromore', 'Kilkeel', 
                            'Castlewellan', 'Ballynahinch'
                        ]
                    ],
                    'Londonderry' => [
                        'cities' => [
                            'Derry', 'Limavady', 'Coleraine', 'Strabane', 
                            'Magherafelt', 'Portstewart', 'Ballykelly', 
                            'Dungiven'
                        ]
                    ],
                    'Tyrone' => [
                        'cities' => [
                            'Omagh', 'Strabane', 'Dungannon', 'Cookstown', 
                            'Castlederg', 'Fintona', 'Gortin'
                        ]
                    ],
                ]
            ],
        ];
        

        foreach ($uk_locations as $reg=>$regions) {
            $checkDiv = Division::where(['country_id'=>'7', 'name'=>$reg]);
            if($checkDiv->count() <1){
                $divison = Division::create(['country_id'=>'7', 'name'=>$reg]);
            }
            else $divison = $checkDiv->first();
        
            echo $reg.':<br/><hr/>';
           
           
            foreach($regions['counties'] as $state=>$cities){
                // dd( $reg, $regions['counties'], $cities['cities']);
                $checkDis = District::where(['division_id'=>$divison->id, 'name'=>$state]);
                if($checkDis->count() <1){
                    $district = District::create(['division_id'=>$divison->id, 'name'=>$state]);
                }
                else $district = $checkDis->first();

                echo '&nbsp; -'.$state.'<br/>';
                foreach($cities['cities'] as $city){
                    $checkCity = City::where(['district_id'=>$district->id, 'name'=>$city]);
                    if($checkCity->count() <1){
                        City::create(['district_id'=>$district->id, 'name'=>$city]);
                    }
                    echo '&nbsp; &nbsp; -'.$city.'<br/>';
                }
            }
            echo '<br/></hr><br/>';
        }
    }


    function reArrange_orders(){
        $orders = Order::all();
        foreach($orders as $order){
            $customer = $order->customer;
            if($customer !=null){
                if($order->customer->district!=null && $order->customer->city !=null){
                    if($order->customer->division==null){
                        $divId = \DB::table('districts')->where('id',$order->customer->district_id)->pluck('division_id')->first();
                        $div = \DB::table('divisions')->where('id',$divId)->pluck('name')->first();
                    }else $div = $order->customer->division->name;
    
                    $data = [
                        'division'=>$div,
                        'district'=>$order->customer->district->name,
                        'city'=>$order->customer->city->name,
                        'first_name'=> $order->customer->first_name,
                        'last_name'=> $order->customer->last_name,
                        'phone'=>$order->customer->phone,
                        'email'=>$order->customer->email,
                        'address'=>$order->customer->address,
                    ];
                    $order->update($data);
                }
            }

            $shipping_address = $order->shipping_address;
            if($shipping_address !=null){
                if($order->customer->district!=null && $order->customer->city !=null){
                    if($order->shipping_address->division==null){
                        $divId = \DB::table('districts')->where('id',$order->shipping_address->district_id)->pluck('division_id')->first();
                        $div = \DB::table('divisions')->where('id',$divId)->pluck('name')->first();
                    }else $div = $order->shipping_address->division->name;
    
                    $data = [
                        'ship_division'=>$div,
                        'ship_district'=>$order->shipping_address->district->name,
                        'ship_city'=>$order->shipping_address->city->name,
                        'ship_first_name'=> $order->shipping_address->fname,
                        'ship_last_name'=> $order->shipping_address->lname,
                        'ship_phone'=>$order->shipping_address->phone,
                        'ship_email'=>$order->shipping_address->email,
                        'ship_address'=>$order->shipping_address->address,
                    ];
                    $order->update($data);
                }
            }
        }
        dd($orders);
    }


    function update_thumbs(){
        $products = \App\Models\Product::all();
        // update database records
        foreach($products as $product){
            if($product->thumbs==null){
                \App\Models\Product::where('id',$product->id)->update([
                    'thumbs'=> str_replace('feature','thumbs',$product->feature_photo)
                ]);
            }else{
                // \App\Models\Product::where('id',$product->id)->update([ 'thumbs'=> null ]);
            }
        }

        // copy feature photo into thumbs & reduct photo size
        foreach($products as $product){
            if (file_exists(public_path('storage/'.$product->feature_photo))) {
                \File::copy(public_path('storage/'.$product->feature_photo), public_path('storage/'.$product->thumbs) );

                $thumbs = Image::make(public_path('storage/'.$product->thumbs));

                // product sizing
                $width = Setting::where('type','product-weight')->pluck('value')->first();
                $height = Setting::where('type','product-height')->pluck('value')->first();
                $thumbsHeight = 288;
                $divide = $height/ $thumbsHeight;
                $thumbsWeight = $width /$divide;

                $thumbs->resize($thumbsHeight, $thumbsWeight);
                $thumbs->save('storage/'.$product->thumbs);
            } else{ echo 'NO file';}
        }
    }

    function update_groupIdIn($type){
        if($type=='inner'){
            $inner_group_products = \App\Models\Inner_group_product::all();
            foreach($inner_group_products as $igp){
                echo 'Inner: '.$igp->inner_group_id.', Group: '.$igp->inner_group->group_id.'<br/>';

                $innerProduct = \App\Models\Inner_group_product::where(['product_id'=>$igp->product_id]);
                $data = [ 'group_id'=>$igp->inner_group->group_id];
                $innerProduct->update($data);
            }
        }

        if($type=='child'){
            $child_group_products = \App\Models\Child_group_product::all();
            foreach($child_group_products as $cgp){
                echo 'Child: '.$cgp->child_group_id.', InnerGroup: '.$cgp->child_group->inner_group_id.', Group:'.$cgp->child_group->inner_group->group_id.'<br/>';

                $innerProduct = \App\Models\Child_group_product::where(['product_id'=>$cgp->product_id]);
                $data = [ 'group_id'=>$cgp->child_group->inner_group->group_id, 'inner_group_id'=>$cgp->child_group->inner_group_id];
                $innerProduct->update($data);
            }
        }
    }

    function set_customer_phone(){
        foreach(Customer::all() as $customer){
            // echo $customer->user->phone.'<br/>';
            if($customer->phone ==null){
                $customer->update(['phone'=>$customer->user->phone]);
            }
        }
    }


    function homePageVideo(Request $request, $lang){
        $ids = \App\Models\Country_video::where('country_id',session('user_currency')->id)->select('video_id')->distinct()->get()->toArray();
       
        $video = Video::whereIn('id',$ids)->where(['url'=>'home','status'=>'1'])->select(['url','type','video_link'])->first();
        return view('includes.video',compact('video'));
    }
    function homePageBlog(Request $request, $lang){
        $ids = \App\Models\Blog_country::where('country_id',session('user_currency')->id)->select('blog_id')->get()->toArray();
        $blogs = Blog::whereIn('id',$ids)->where('status','1')->select(['id','title','slug','photo','created_at'])->orderByRaw('RAND()')->take(6)->get();
        return view('includes.blog',compact('blogs'));
    }
    function homePageHighlight(Request $request, $lang){
        $ids = \App\Models\Country_highlight::where('country_id',session('user_currency')->id)->select('highlight_id')->distinct()->get()->toArray();
        $highlights = Highlight::whereIn('id',$ids)->where('status','1')->select(['id','photo','title'])->orderByRaw('RAND()')->get();
        return view('includes.highlights',compact('highlights'));

    }
    function homePageCategory(Request $request, $lang){
        $ids = \App\Models\Country_group::where('country_id',session('user_currency')->id)->select('group_id')->distinct()->get()->toArray();
        $categories = Group::whereIn('id',$ids)->where('status','1')->select(['title','slug','photo'])->orderBy('sort_by')->get();
        return view('includes.category',compact('categories'));
    }

    function homePageSubCategory(Request $request, $lang){
        $ids = \App\Models\Country_inner_group::where('country_id',session('user_currency')->id)->select('inner_group_id')->distinct()->get()->toArray();
        $sub_categories = Inner_group::whereIn('id',$ids)->where('status','1')->select(['title','slug','photo'])->orderBy('sort_by')->get();
        return view('includes.sub-categories',compact('sub_categories'));
    }
    

    public function change_currency(Request $request, $lang){
    	$lang = strtolower($lang);

    	// Fetch currency data from the Country model
    	$currency = \App\Models\Country::select([
        'id', 'name', 'short_name', 'short_code', 
        'currency_code', 'phone_code', 'flag', 
        'currencySymbol', 'currencyValue', 'zone'
    	])->where('short_name', $lang)->first();

    	if (!$currency) {
        	return redirect()->route('home')->with('error', 'Currency not found.');
    	}

    	// Store user currency in session
   	 session()->put('user_currency', $currency);

    	// Set locale
    	app()->setLocale($lang);
    	session()->put('locale', $lang);
    	Config::set('app.locale', $lang);

    	//return $this->index($request, $lang);
	return redirect()->route('home', $lang);



   }



public function dashboard(Request $request)
{
    if (!Auth::check() || Auth::user()->status == '0') {
        auth()->logout(); 
        return redirect()->to('/login?r=no-access');
    }

    // Determine the correct dashboard based on user type
    switch (Auth::user()->user_type_id) {
        case 1:
            return redirect()->route('superAdmin.dashboard');
        case 2:
            return redirect()->route('admin.dashboard');
        case 3:
            return redirect()->route('staff.dashboard');
        case 4:
            $currency = strtolower(session('user_currency')->short_name ?? 'usd'); // Fallback to 'usd'
            return redirect()->to("{$currency}/customer/dashboard");
        default:
            auth()->logout();
            return redirect('/login');
    }
}

    function category_products($slug=null){ return view('products'); }

    function wishlist(){
        $wishlists = Wishlist::where('session_id',session()->get('session_id'))->get();
        return view('wishlist', compact('wishlists'));
    }

    function categories(){
        $categories = Group::where('status','1')->orderBy('sort_by')->get();
        return view('categories', compact('categories'));
    }

    public function autocompleteSearch(Request $request) {
        
        $ids = \App\Models\Country_product::where('country_id',session('user_currency')->id)->select('product_id')->distinct()->get()->toArray();
        $products = Product::whereIn('id',$ids)->where('status', '1')
            ->where('title', 'LIKE', '%' . $request->term . '%')
            ->orWhere('tags', 'LIKE', "%{$request->term}%")
            ->orWhere('slug', 'LIKE', "%{$request->term}%")
            ->orWhere('design_code', $request->term)
            ->select(['title', 'thumbs', 'sale_price'])
            // ->orderByRaw("CASE WHEN title LIKE '%$request->term%' THEN 1 ELSE 2 END")
            // ->orderByRaw("CASE WHEN tags LIKE '%$request->term%' THEN 1 ELSE 2 END")
            // ->orderByRaw("CASE WHEN slug LIKE '%$request->term%' THEN 1 ELSE 2 END")
            ->take(10)->get();


        $output = array();
        if($products->count() > 0){
            foreach($products as $row){
                $temp_array = array();
                $temp_array['value'] = $row->title;
                $temp_array['label'] = '<p class="p-2"><img src="'.$row->thumbs.'" width="20" /> '.$row->title.', Regular price: '.$row->sale_price.'</p>';
                $output[] = $temp_array;
            }
        }else {
            $output['value'] = '.................. no match found.................';
            $output['label'] = 'No record matching with given keyword';
        }
        echo json_encode($output);
    }

    function policy($slug){
        if($slug=='all'){
            $type = new Policy_type();
            $policies = Policy::orderBy('title')->get();
            return view('policy',compact('policies','type'));
        }else{
            $type = Policy_type::where('slug',$slug)->first();
            if($type !=null){
                $policies = Policy::where('policy_type_id',$type->id)->get();
                return view('policy',compact('policies','type'));
            }else return view('errors.404');
        }
    }

    function page_info($slug){
        $type = Page_post_type::where('slug',$slug)->first();
        if($type !=null){
            $posts = Page_post::where('page_post_type_id',$type->id)->get();
            return view('page',compact('posts','type'));
        }else return view('errors.404');
    }

    function faqs(){
        $ids = \App\Models\Country_faq::where('country_id',session('user_currency')->id)->select('faq_id')->distinct()->get()->toArray();
        $posts = Faq::whereIn('id',$ids)->where('status','1')->paginate(20);
        return view('faqs',compact('posts'));
    }

    function save_contact(Request $request){
        $validator = $this->fields();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'name'=>$request->name, 'email'=>$request->email , 'ip'=>$request->ip(),
            'phone'=>$request->phone, 'subject'=>$request->subject, 'message'=>$request->message
        ];

        Contact::create($data);
        return response()->json(['success' => 'Your message has been sent successfully!']);
    }
    

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'name'=>'required',
            'email'=>'sometimes|nullable|email',
            'phone'=>'required',
            'subject'=>'required',
            'message'=>'required|min:15',
        ]); return $validator;
    }


    function districts($division){
        return DB::table('districts')->select('id','name')->where([ 'division_id'=>explode('|',$division)[0] ])->get();
    }

    function cities($district){
        return DB::table('cities')->select('id','name')->where(['district_id'=>explode('|',$district)[0] ])->get();
    }

    function district_delivery_cost(District $district){
        return 'Your district is: '.$district->name.' and Delivery cost is '.$district->delivery_cost;
    }

    public function zone_from_city(Request $request, $lang, City $city, $subtotal){
        $cityZone = City_zone::where('city_id',$city->id)->first();
        if($cityZone !=null){
            $data[0] = '<label for="shipping">
                <input id="shipping" type="radio" class="input-radio" name="zone" value="'.$cityZone->zone_id.'" checked/> '.$cityZone->zone->name.'
                <div class="payment_box" >Duration: '.$cityZone->zone->duration. ', &nbsp; Charge: '.session()->get('user_currency')->symbol.' '.number_format(deliveryCharge($cityZone->zone->delivery_cost),3).'
                <br> <p>'.$cityZone->zone->description.'</p>
                </div>
            </label>';

            $invoice_dis = 0;
            $checkInvoiceDiscount = \App\Models\Invoice_discount::where('status','1')->select('id','type','min_order_amount','discount_in','discount_value');
            if ($checkInvoiceDiscount->count()==1 && $checkInvoiceDiscount->first()->type=='free-delivery' && $subtotal >= $checkInvoiceDiscount->first()->min_order_amount){
                $invoice_dis = $cityZone->zone->delivery_cost;
                $data[1] = number_format(deliveryCharge($cityZone->zone->delivery_cost - $invoice_dis),3);
            }else{
                $data[1] = $cityZone->zone->delivery_cost;
            }
        
        }else{
            $data[0] = '<label for="shipping">
                <input id="shipping"  type="radio" class="input-radio" name="zone" value="" checked/>No charge defined
                <div class="payment_box" >Duration:  <span class="text-warning"> Not defined </span>&nbsp;
                <br> <p class="text-warning">System entity error. <b class="text-primary">Or</b> <br/><span class="text-info">The option may be activated later with auto delivery charge</span></p>
                </div>
            </label>';
            $data[1] = 0;
        }
        return $data;
    }


    function showrooms(Request $request){
        $dis_ids = Show_room::where('district_id','!=', null)->select('district_id')->distinct()->get()->toArray();
        $districts = \App\Models\District::whereIn('id',$dis_ids)->get();

        if($request->district){
            $showrooms = Show_room::where(['status'=>'1', 'district_id'=>$request->district])->orderBy('district_id')->get();
        }else{
            $showrooms = Show_room::where('status','1')->orderBy('district_id')->get();
        }
        

        return view('showrooms',compact('showrooms','districts'));
    }

    function showroom_map(Show_room $show_room){
        return $show_room->embed_code;

        // $response = \GoogleMaps::load('geocoding')
		// ->setParam (['address' =>'santa cruz'])
 		// ->get();
        // dd($response);
    }


    function size_guird(){ return view('size-guird');}

    function subscribe(Request $request){
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }else{

            $check = Subscriber::where('email',$request->email);
            if($check->count()< 1){
                $subcribe = Subscriber::create(['ip'=>$request->ip(), 'email'=>$request->email]);
                session(['subcriber' => $subcribe->email]);
            }
        }
        return back();
    }

    function instagram_feed(){
        $profile = \Dymantic\InstagramFeed\Profile::where('username','mbrella_fashion')->first();
    
        //get authenticatino url => go to this url to authenticate
        // dd($profile->getInstagramAuthUrl());
        $feeds = $profile->refreshFeed(6);
        dd($feeds);
    }

    

    function product_meta(Product $product){
        return $product->product_metas()->select('meta_type','meta_content')->get();
    }

    function sitemap(){ return view('sitemap');}

    function outlet_customers(){
        $outletCustomer = null; $outletDiscount = 0;
        try {
            if(auth()->check()){
                DB::connection('sqlsrv')->getPDO();
                $outletCustomer = DB::connection('sqlsrv')->table('customers')->where('phone',Auth::user()->phone)->first();
                if($outletCustomer !=null){
                    $outletDiscount = outlet_customer_discount($outletCustomer);
                    session()->put('outlet_customer',$outletCustomer);
                    return response()->json(['outletDiscount' => $outletDiscount, 'outletCustomer'=>$outletCustomer]);
                }
            }else{
                return '';
                // return response()->json(['error'=>'Customer have to login first for outlet discount']);
            }
        }
        catch (\Exception $e) {
           dd($e);
        }
    }

    
}

