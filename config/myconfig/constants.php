<?php
return [
    'textheader' => 'test',
    'tracking_type' => [
        'link' => '1',
        'coupon' => '2'
    ],
    'commission_type' => [
        'percent_of_sale' => 1,
        'flat_rate_order' => 2,
        'flat_rate_item' => 3
    ],
    'payment_method_default' => [
        'paypal' => [
            "name" => "Paypal",
            "key" => "paypal",
            "is_enable" => true,
            "fields" => [
                [
                    'type' => "input",
                    'inputType' => "email",
                    'label'=> "Email",
                    'model' => "email",
                    'required' => true,
                ],
            ]
        ],
        'bank_transfer' => [
            "name" => "Bank Transfer",
            "key" => "bank_transfer",
            "is_enable" => false,
            "fields" => [
                [
                    'type' => "select",
                    'label' => "Account Type",
                    'model' => "account_type",
                    'values'=> [
                        "Checking",
                        "Saving"
                    ]
                ],
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label'=> "Bank name",
                    'model' => "bank_name",
                    'required' => true,
                ],
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label'=> "Account name",
                    'model' => "account_name",
                    'required' => true,
                ],
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label'=> "Account number",
                    'model' => "account_number",
                    'required' => true,
                ],
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label'=> "Branch Code",
                    'model' => "branch_ode",
                   
                ],
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label'=> "Swift Code",
                    'model' => "swift_code",
                    
                ],
            ],
        ],
        'debit_card' => [
            "name" => "Debit Card",
            "key" => "debit_card",
            "is_enable" => false,
            "fields" => [
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label' => "Name",
                    'model' => "name",
                    'required' => true,
                    
                ],
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label' => "Debit Card Number",
                    'model' => "card_number",
                    'required' => true,
                    
                ],
            ]
        ],
        'cash' => [
            "name" => "Cash",
            "key" => "cash",
            "is_enable" => false,
            "fields" => []
        ],
        'payoneer' => [
            "name" => "Payoneer",
            "key" => "payoneer",
            "is_enable" => false,
            "fields" => [
                [
                    'type' => "input",
                    'inputType' => "email",
                    'label' => "Payoneer email address",
                    'model' => "email_address",
                    'required' => true,
                    
                ],
            ]
        ],

        'google_pay' => [
            "name" => "Google pay",
            "key" => "google_pay",
            "is_enable" => false,
            "fields" => [
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label' => "ID/Email",
                    'model' => "id",
                    'required' => true,
                    
                ],
            ]
        ],
        'venmo' => [
            "name" => "Venmo",
            "key" => "venmo",
            "is_enable" => false,
            "fields" => [
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label' => "Mobile number or Username",
                    'model' => "username",
                    'required' => true,
                    
                ],
            ]
        ],
        'cash_app' => [
            "name" => "CashApp",
            "key" => "cashapp",
            "is_enable" => false,
            "fields" => [
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label' => "Phone/Email/Cashtag",
                    'model' => "phone_email_cashtag",
                    'required' => true,
                    
                ],
            ]
        ],
        'cheque' => [
            "name" => "Cheque",
            "key" => "cheque",
            "is_enable" => false,
            "fields" => [
                [
                    'type' => "input",
                    'inputType' => "text",
                    'label' => "Pay to",
                    'model' => "pay_to",
                    'required' => true,
                    
                ],
                [
                    'type' => "textArea",
                    'label' => "Address",
                    'model' => "address",
                    'required' => true,
                    'rows'=> 4,
                    
                ],
            ]
        ],
        
    ],

    'all_timezone' => '{"2021-11-30 23:59:59":["Africa\/Abidjan","Africa\/Accra","Africa\/Bamako","Africa\/Banjul","Africa\/Bissau","Africa\/Conakry","Africa\/Dakar","Africa\/Freetown","Africa\/Lome","Africa\/Monrovia","Africa\/Nouakchott","Africa\/Ouagadougou","Africa\/Sao_Tome","America\/Danmarkshavn","Antarctica\/Troll","Atlantic\/Canary","Atlantic\/Faroe","Atlantic\/Madeira","Atlantic\/Reykjavik","Atlantic\/St_Helena","Europe\/Dublin","Europe\/Guernsey","Europe\/Isle_of_Man","Europe\/Jersey","Europe\/Lisbon","Europe\/London","UTC"],"2021-11-30 20:59:59":["Africa\/Addis_Ababa","Africa\/Asmara","Africa\/Dar_es_Salaam","Africa\/Djibouti","Africa\/Kampala","Africa\/Mogadishu","Africa\/Nairobi","Antarctica\/Syowa","Asia\/Aden","Asia\/Baghdad","Asia\/Bahrain","Asia\/Kuwait","Asia\/Qatar","Asia\/Riyadh","Europe\/Istanbul","Europe\/Kirov","Europe\/Minsk","Europe\/Moscow","Europe\/Simferopol","Europe\/Volgograd","Indian\/Antananarivo","Indian\/Comoro","Indian\/Mayotte"],"2021-11-30 22:59:59":["Africa\/Algiers","Africa\/Bangui","Africa\/Brazzaville","Africa\/Casablanca","Africa\/Ceuta","Africa\/Douala","Africa\/El_Aaiun","Africa\/Kinshasa","Africa\/Lagos","Africa\/Libreville","Africa\/Luanda","Africa\/Malabo","Africa\/Ndjamena","Africa\/Niamey","Africa\/Porto-Novo","Africa\/Tunis","Arctic\/Longyearbyen","Europe\/Amsterdam","Europe\/Andorra","Europe\/Belgrade","Europe\/Berlin","Europe\/Bratislava","Europe\/Brussels","Europe\/Budapest","Europe\/Busingen","Europe\/Copenhagen","Europe\/Gibraltar","Europe\/Ljubljana","Europe\/Luxembourg","Europe\/Madrid","Europe\/Malta","Europe\/Monaco","Europe\/Oslo","Europe\/Paris","Europe\/Podgorica","Europe\/Prague","Europe\/Rome","Europe\/San_Marino","Europe\/Sarajevo","Europe\/Skopje","Europe\/Stockholm","Europe\/Tirane","Europe\/Vaduz","Europe\/Vatican","Europe\/Vienna","Europe\/Warsaw","Europe\/Zagreb","Europe\/Zurich"],"2021-11-30 21:59:59":["Africa\/Blantyre","Africa\/Bujumbura","Africa\/Cairo","Africa\/Gaborone","Africa\/Harare","Africa\/Johannesburg","Africa\/Juba","Africa\/Khartoum","Africa\/Kigali","Africa\/Lubumbashi","Africa\/Lusaka","Africa\/Maputo","Africa\/Maseru","Africa\/Mbabane","Africa\/Tripoli","Africa\/Windhoek","Asia\/Amman","Asia\/Beirut","Asia\/Damascus","Asia\/Famagusta","Asia\/Gaza","Asia\/Hebron","Asia\/Jerusalem","Asia\/Nicosia","Europe\/Athens","Europe\/Bucharest","Europe\/Chisinau","Europe\/Helsinki","Europe\/Kaliningrad","Europe\/Kiev","Europe\/Mariehamn","Europe\/Riga","Europe\/Sofia","Europe\/Tallinn","Europe\/Uzhgorod","Europe\/Vilnius","Europe\/Zaporozhye"],"2021-12-01 09:59:59":["America\/Adak","Pacific\/Honolulu","Pacific\/Rarotonga","Pacific\/Tahiti"],"2021-12-01 08:59:59":["America\/Anchorage","America\/Juneau","America\/Metlakatla","America\/Nome","America\/Sitka","America\/Yakutat","Pacific\/Gambier"],"2021-12-01 03:59:59":["America\/Anguilla","America\/Antigua","America\/Aruba","America\/Barbados","America\/Blanc-Sablon","America\/Boa_Vista","America\/Campo_Grande","America\/Caracas","America\/Cuiaba","America\/Curacao","America\/Dominica","America\/Glace_Bay","America\/Goose_Bay","America\/Grenada","America\/Guadeloupe","America\/Guyana","America\/Halifax","America\/Kralendijk","America\/La_Paz","America\/Lower_Princes","America\/Manaus","America\/Marigot","America\/Martinique","America\/Moncton","America\/Montserrat","America\/Port_of_Spain","America\/Porto_Velho","America\/Puerto_Rico","America\/Santo_Domingo","America\/St_Barthelemy","America\/St_Kitts","America\/St_Lucia","America\/St_Thomas","America\/St_Vincent","America\/Thule","America\/Tortola","Atlantic\/Bermuda"],"2021-12-01 02:59:59":["America\/Araguaina","America\/Argentina\/Buenos_Aires","America\/Argentina\/Catamarca","America\/Argentina\/Cordoba","America\/Argentina\/Jujuy","America\/Argentina\/La_Rioja","America\/Argentina\/Mendoza","America\/Argentina\/Rio_Gallegos","America\/Argentina\/Salta","America\/Argentina\/San_Juan","America\/Argentina\/San_Luis","America\/Argentina\/Tucuman","America\/Argentina\/Ushuaia","America\/Asuncion","America\/Bahia","America\/Belem","America\/Cayenne","America\/Fortaleza","America\/Maceio","America\/Miquelon","America\/Montevideo","America\/Nuuk","America\/Paramaribo","America\/Punta_Arenas","America\/Recife","America\/Santarem","America\/Santiago","America\/Sao_Paulo","Antarctica\/Palmer","Antarctica\/Rothera","Atlantic\/Stanley"],"2021-12-01 04:59:59":["America\/Atikokan","America\/Bogota","America\/Cancun","America\/Cayman","America\/Detroit","America\/Eirunepe","America\/Grand_Turk","America\/Guayaquil","America\/Havana","America\/Indiana\/Indianapolis","America\/Indiana\/Marengo","America\/Indiana\/Petersburg","America\/Indiana\/Vevay","America\/Indiana\/Vincennes","America\/Indiana\/Winamac","America\/Iqaluit","America\/Jamaica","America\/Kentucky\/Louisville","America\/Kentucky\/Monticello","America\/Lima","America\/Nassau","America\/New_York","America\/Nipigon","America\/Panama","America\/Pangnirtung","America\/Port-au-Prince","America\/Rio_Branco","America\/Thunder_Bay","America\/Toronto","Pacific\/Easter"],"2021-12-01 05:59:59":["America\/Bahia_Banderas","America\/Belize","America\/Chicago","America\/Costa_Rica","America\/El_Salvador","America\/Guatemala","America\/Indiana\/Knox","America\/Indiana\/Tell_City","America\/Managua","America\/Matamoros","America\/Menominee","America\/Merida","America\/Mexico_City","America\/Monterrey","America\/North_Dakota\/Beulah","America\/North_Dakota\/Center","America\/North_Dakota\/New_Salem","America\/Rainy_River","America\/Rankin_Inlet","America\/Regina","America\/Resolute","America\/Swift_Current","America\/Tegucigalpa","America\/Winnipeg","Pacific\/Galapagos"],"2021-12-01 06:59:59":["America\/Boise","America\/Cambridge_Bay","America\/Chihuahua","America\/Creston","America\/Dawson","America\/Dawson_Creek","America\/Denver","America\/Edmonton","America\/Fort_Nelson","America\/Hermosillo","America\/Inuvik","America\/Mazatlan","America\/Ojinaga","America\/Phoenix","America\/Whitehorse","America\/Yellowknife"],"2021-12-01 07:59:59":["America\/Los_Angeles","America\/Tijuana","America\/Vancouver","Pacific\/Pitcairn"],"2021-12-01 01:59:59":["America\/Noronha","Atlantic\/South_Georgia"],"2021-12-01 00:59:59":["America\/Scoresbysund","Atlantic\/Azores","Atlantic\/Cape_Verde"],"2021-12-01 03:29:59":["America\/St_Johns"],"2021-11-30 12:59:59":["Antarctica\/Casey","Antarctica\/Macquarie","Asia\/Magadan","Asia\/Sakhalin","Asia\/Srednekolymsk","Australia\/Hobart","Australia\/Lord_Howe","Australia\/Melbourne","Australia\/Sydney","Pacific\/Bougainville","Pacific\/Efate","Pacific\/Guadalcanal","Pacific\/Kosrae","Pacific\/Noumea","Pacific\/Pohnpei"],"2021-11-30 16:59:59":["Antarctica\/Davis","Asia\/Bangkok","Asia\/Barnaul","Asia\/Ho_Chi_Minh","Asia\/Hovd","Asia\/Jakarta","Asia\/Krasnoyarsk","Asia\/Novokuznetsk","Asia\/Novosibirsk","Asia\/Phnom_Penh","Asia\/Pontianak","Asia\/Tomsk","Asia\/Vientiane","Indian\/Christmas"],"2021-11-30 13:59:59":["Antarctica\/DumontDUrville","Asia\/Ust-Nera","Asia\/Vladivostok","Australia\/Brisbane","Australia\/Lindeman","Pacific\/Chuuk","Pacific\/Guam","Pacific\/Port_Moresby","Pacific\/Saipan"],"2021-11-30 18:59:59":["Antarctica\/Mawson","Asia\/Aqtau","Asia\/Aqtobe","Asia\/Ashgabat","Asia\/Atyrau","Asia\/Dushanbe","Asia\/Karachi","Asia\/Oral","Asia\/Qyzylorda","Asia\/Samarkand","Asia\/Tashkent","Asia\/Yekaterinburg","Indian\/Kerguelen","Indian\/Maldives"],"2021-11-30 10:59:59":["Antarctica\/McMurdo","Pacific\/Auckland","Pacific\/Enderbury","Pacific\/Fakaofo","Pacific\/Fiji","Pacific\/Tongatapu"],"2021-11-30 17:59:59":["Antarctica\/Vostok","Asia\/Almaty","Asia\/Bishkek","Asia\/Dhaka","Asia\/Omsk","Asia\/Qostanay","Asia\/Thimphu","Asia\/Urumqi","Indian\/Chagos"],"2021-11-30 11:59:59":["Asia\/Anadyr","Asia\/Kamchatka","Pacific\/Funafuti","Pacific\/Kwajalein","Pacific\/Majuro","Pacific\/Nauru","Pacific\/Norfolk","Pacific\/Tarawa","Pacific\/Wake","Pacific\/Wallis"],"2021-11-30 19:59:59":["Asia\/Baku","Asia\/Dubai","Asia\/Muscat","Asia\/Tbilisi","Asia\/Yerevan","Europe\/Astrakhan","Europe\/Samara","Europe\/Saratov","Europe\/Ulyanovsk","Indian\/Mahe","Indian\/Mauritius","Indian\/Reunion"],"2021-11-30 15:59:59":["Asia\/Brunei","Asia\/Choibalsan","Asia\/Hong_Kong","Asia\/Irkutsk","Asia\/Kuala_Lumpur","Asia\/Kuching","Asia\/Macau","Asia\/Makassar","Asia\/Manila","Asia\/Shanghai","Asia\/Singapore","Asia\/Taipei","Asia\/Ulaanbaatar","Australia\/Perth"],"2021-11-30 14:59:59":["Asia\/Chita","Asia\/Dili","Asia\/Jayapura","Asia\/Khandyga","Asia\/Pyongyang","Asia\/Seoul","Asia\/Tokyo","Asia\/Yakutsk","Pacific\/Palau"],"2021-11-30 18:29:59":["Asia\/Colombo","Asia\/Kolkata"],"2021-11-30 19:29:59":["Asia\/Kabul"],"2021-11-30 18:14:59":["Asia\/Kathmandu"],"2021-11-30 20:29:59":["Asia\/Tehran"],"2021-11-30 17:29:59":["Asia\/Yangon","Indian\/Cocos"],"2021-11-30 13:29:59":["Australia\/Adelaide","Australia\/Broken_Hill"],"2021-11-30 14:29:59":["Australia\/Darwin"],"2021-11-30 15:14:59":["Australia\/Eucla"],"2021-11-30 09:59:59":["Pacific\/Apia","Pacific\/Kiritimati"],"2021-11-30 10:14:59":["Pacific\/Chatham"],"2021-12-01 09:29:59":["Pacific\/Marquesas"],"2021-12-01 10:59:59":["Pacific\/Midway","Pacific\/Niue","Pacific\/Pago_Pago"]}'

    


];
