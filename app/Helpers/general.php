<?php

if (!function_exists('_lang')) {
    function _lang($string = '') {

        $target_lang = get_language();

        if ($target_lang == '') {
            $target_lang = "language";
        }

        if (file_exists(resource_path() . "/language/$target_lang.php")) {
            include resource_path() . "/language/$target_lang.php";
        } else {
            include resource_path() . "/language/language.php";
        }

        if (array_key_exists($string, $language)) {
            return $language[$string];
        } else {
            return $string;
        }
    }
}

if (!function_exists('_dlang')) {
    function _dlang($string = '') {

        //Get Target language
        $target_lang = get_option('language');

        if ($target_lang == '') {
            $target_lang = 'language';
        }

        if (file_exists(resource_path() . "/language/$target_lang.php")) {
            include resource_path() . "/language/$target_lang.php";
        } else {
            include resource_path() . "/language/language.php";
        }

        if (array_key_exists($string, $language)) {
            return $language[$string];
        } else {
            return $string;
        }
    }
}

if (!function_exists('startsWith')) {
    function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}

if (!function_exists('get_initials')) {
    function get_initials($string) {
        $words    = explode(" ", $string);
        $initials = null;
        foreach ($words as $w) {
            $initials .= $w[0];
        }
        return $initials;
    }
}

if (!function_exists('create_option')) {
    function create_option($table, $value, $display, $selected = '', $where = NULL) {
        $options   = '';
        $condition = '';
        if ($where != NULL) {
            $condition .= "WHERE ";
            foreach ($where as $key => $v) {
                $condition .= $key . "'" . $v . "' ";
            }
        }

        if (is_array($display)) {
            $display_array = $display;
            $display       = $display_array[0];
            $display1      = $display_array[1];
        }

        $query = DB::select("SELECT * FROM $table $condition");
        foreach ($query as $d) {
            if ($selected != '' && $selected == $d->$value) {
                if (!isset($display_array)) {
                    $options .= "<option value='" . $d->$value . "' selected='true'>" . ucwords($d->$display) . "</option>";
                } else {
                    $options .= "<option value='" . $d->$value . "' selected='true'>" . ucwords($d->$display . ' - ' . $d->$display1) . "</option>";
                }
            } else {
                if (!isset($display_array)) {
                    $options .= "<option value='" . $d->$value . "'>" . ucwords($d->$display) . "</option>";
                } else {
                    $options .= "<option value='" . $d->$value . "'>" . ucwords($d->$display . ' - ' . $d->$display1) . "</option>";
                }
            }
        }

        echo $options;
    }
}

if (!function_exists('object_to_string')) {
    function object_to_string($object, $col, $quote = false) {
        $string = "";
        foreach ($object as $data) {
            if ($quote == true) {
                $string .= "'" . $data->$col . "', ";
            } else {
                $string .= $data->$col . ", ";
            }
        }
        $string = substr_replace($string, "", -2);
        return $string;
    }
}

if (!function_exists('get_table')) {
    function get_table($table, $where = NULL) {
        $condition = "";
        if ($where != NULL) {
            $condition .= "WHERE ";
            foreach ($where as $key => $v) {
                $condition .= $key . "'" . $v . "' ";
            }
        }
        $query = DB::select("SELECT * FROM $table $condition");
        return $query;
    }
}

if (!function_exists('user_count')) {
    function user_count($user_type) {
        $count = \App\Models\User::where("user_type", $user_type)
            ->selectRaw("COUNT(id) as total")
            ->first()->total;
        return $count;
    }
}

if (!function_exists('has_permission')) {
    function has_permission($name) {
        $user = auth()->user();
        if ($user->user_type == 'admin' || $user->user_type == 'manager') {
            return true;
        }
        $permission_list = $user->role->permissions;
        $permission      = $permission_list->firstWhere('permission', $name);

        if ($permission != null) {
            return true;
        }
        return false;
    }
}

if (!function_exists('get_logo')) {
    function get_logo() {
        $logo = get_option("logo");
        if ($logo == "") {
            return asset("public/backend/images/company-logo.png");
        }
        return asset("public/uploads/media/$logo");
    }
}

if (!function_exists('get_favicon')) {
    function get_favicon() {
        $favicon = get_option("favicon");
        if ($favicon == "") {
            return asset("public/backend/images/favicon.png");
        }
        return asset("public/uploads/media/$favicon");
    }
}

if (!function_exists('profile_picture')) {
    function profile_picture($profile_picture = '') {
        if ($profile_picture == '') {
            return asset('public/backend/images/avatar.png');
        }

        return asset('public/uploads/profile/' . $profile_picture);
    }
}

if (!function_exists('media_image')) {
    function media_images($image) {
        if ($image == null || $image == '') {
            return asset('public/uploads/media/default.png');
        }
        return asset("public/uploads/media/$image");
    }
}

if (!function_exists('sql_escape')) {
    function sql_escape($unsafe_str) {
        if (get_magic_quotes_gpc()) {
            $unsafe_str = stripslashes($unsafe_str);
        }
        return $escaped_str = str_replace("'", "", $unsafe_str);
    }
}

if (!function_exists('get_option')) {
    function get_option($name, $optional = '') {
        $value = Cache::get($name);

        if ($value == "") {
            $setting = DB::table('settings')->where('name', $name)->get();
            if (!$setting->isEmpty()) {
                $value = $setting[0]->value;
                Cache::put($name, $value);
            } else {
                $value = $optional;
            }
        }
        return $value;

    }
}

if (!function_exists('get_setting')) {
    function get_setting($settings, $name, $optional = '') {
        $row = $settings->firstWhere('name', $name);
        if ($row != null) {
            return $row->value;
        }
        return $optional;

    }
}

if (!function_exists('get_trans_option')) {
    function get_trans_option($name, $optional = '') {
        $value = Cache::get($name . "-" . get_language());

        if ($value == "" || $value == NULL) {
            $setting = \App\Models\Setting::where('name', $name)->first();

            if ($setting) {
                $value = $setting->translation->value;
                Cache::put($name . "-" . get_language(), $value);
            } else {
                $value = $optional;
                Cache::put($name . "-" . get_language(), $value);
            }
        }

        return $value;
    }
}

if (!function_exists('navigationTree')) {

    function navigationTree($object, $currentParent, $controller, $currLevel = 0, $prevLevel = -1) {
        foreach ($object as $menu) {
            if ($currentParent == $menu->parent_id) {
                if ($currLevel > $prevLevel) {
                    echo "<ol id='menutree' class='dd-list'>";
                }

                if ($currLevel == $prevLevel) {
                    echo "</li>";
                }

                echo '<li class="dd-item" data-id="' . $menu->id . '"><div class="dd-handle">' . $menu->translation->name . '</div><a class="edit_menu" href="' . action("$controller@edit", $menu->id) . '"><i class="icofont-edit"></i></a>
					<a class="btn-remove-2 remove_menu" href="' . action("$controller@destroy", $menu->id) . '"><i class="icofont-trash"></i></a>';
                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                navigationTree($object, $menu->id, $controller, $currLevel, $prevLevel);
                $currLevel--;
            }
        }
        if ($currLevel == $prevLevel) {
            echo "</li> </ol>";
        }

    }
}

if (!function_exists('show_navigation')) {
    function show_navigation($nav_id, $main_class = '', $nav_link_class = '', $dp_1_class = '', $dp_2_class = '', $icon_type = 'down') {

        $navigation = \App\Models\Navigation::where('id', $nav_id)
            ->where('status', 1)->first();
        if ($navigation) {
            $navigation_items = $navigation->navigationItems()->where('status', 1)->get();
            buildNavigation($navigation_items, $main_class, $nav_link_class, $dp_1_class, $dp_2_class, $icon_type);
        }

    }
}

if (!function_exists('buildNavigation')) {

    function buildNavigation($navigation_items, $main_class = '', $nav_link_class = '', $dropdown_1_class = '', $dropdown_2_class = '', $icon_type, $currentParent = 0, $currLevel = 0, $prevLevel = -1) {
        foreach ($navigation_items as $nav_item) {
            if ($currentParent == $nav_item->parent_id) {

                if ($currLevel > $prevLevel && $currLevel == 0) {
                    echo "<ul class='$main_class'>";
                } else if ($currLevel > $prevLevel && $currLevel == 1) {
                    echo "<ul class='$dropdown_1_class'>";
                } else if ($currLevel > $prevLevel && $currLevel == 2) {
                    echo "<ul class='$dropdown_2_class'>";
                }

                if ($currLevel == $prevLevel) {
                    echo "</li>";
                }

                if ($nav_item->type == 'dynamic_url') {
                    $url = url($nav_item->url);
                } else if ($nav_item->type == 'page') {
                    $url = url('/' . $nav_item->page->slug);
                } else if ($nav_item->type == 'category') {
                    $url = url('/categories/' . $nav_item->category->slug);
                } else if ($nav_item->type == 'custom_url') {
                    $url = $nav_item->url;
                }

                $icon      = $nav_item->icon;
                $target    = $nav_item->target;
                $active    = $url == url()->current() ? 'active' : '';
                $css_class = $nav_item->css_class != '' ? "class='$nav_item->css_class $active $nav_link_class'" : "class='$active $nav_link_class'";
                $css_id    = $nav_item->css_id != '' ? "id='$nav_item->css_id'" : "";

                $has_child = '';

                if ($nav_item->child_items->count() > 0 && $currLevel == 0) {
                    $has_child = $icon_type == 'down' ? ' <i class="bi bi-chevron-down"></i>' : ' <i class="bi bi-chevron-right"></i>';
                } else if ($nav_item->child_items->count() > 0 && $currLevel == 1) {
                    $has_child = ' <i class="bi bi-chevron-right"></i>';
                }

                echo '<li class="nav-item"><a target="' . $target . '" href="' . $url . '" ' . $css_class . ' ' . $css_id . '>' . $icon . ' ' . $nav_item->translation->name . ' ' . $has_child . '</a>';

                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }

                $currLevel++;
                buildNavigation($navigation_items, $main_class, $nav_link_class, $dropdown_1_class, $dropdown_2_class, $icon_type, $nav_item->id, $currLevel, $prevLevel);
                $currLevel--;
            }
        }
        if ($currLevel == $prevLevel) {
            echo "</li> </ul>";
        }

    }
}

if (!function_exists('get_array_option')) {
    function get_array_option($name, $key = '', $optional = '') {
        if ($key == '') {
            if (session('language') == '') {
                $key = get_option('language');
                session(['language' => $key]);
            } else {
                $key = session('language');
            }
        }
        $setting = DB::table('settings')->where('name', $name)->get();
        if (!$setting->isEmpty()) {

            $value = $setting[0]->value;
            if (@unserialize($value) !== false) {
                $value = @unserialize($setting[0]->value);

                return isset($value[$key]) ? $value[$key] : $value[array_key_first($value)];
            }

            return $value;
        }
        return $optional;

    }
}

if (!function_exists('get_array_data')) {
    function get_array_data($data, $key = '') {
        if ($key == '') {
            if (session('language') == '') {
                $key = get_option('language');
                session(['language' => $key]);
            } else {
                $key = session('language');
            }
        }

        if (@unserialize($data) !== false) {
            $value = @unserialize($data);
            return isset($value[$key]) ? $value[$key] : $value[array_key_first($value)];
        }

        return $data;

    }
}

if (!function_exists('update_option')) {
    function update_option($name, $value) {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));

        $data               = array();
        $data['value']      = $value;
        $data['updated_at'] = \Carbon\Carbon::now();
        if (\App\Models\Setting::where('name', $name)->exists()) {
            \App\Models\Setting::where('name', $name)->update($data);
        } else {
            $data['name']       = $name;
            $data['created_at'] = \Carbon\Carbon::now();
            \App\Models\Setting::insert($data);
        }
    }
}

if (!function_exists('timezone_list')) {

    function timezone_list() {
        $zones_array = array();
        $timestamp   = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['ZONE'] = $zone;
            $zones_array[$key]['GMT']  = 'UTC/GMT ' . date('P', $timestamp);
        }
        return $zones_array;
    }

}

if (!function_exists('create_timezone_option')) {

    function create_timezone_option($old = "") {
        $option    = "";
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $selected = $old == $zone ? "selected" : "";
            $option .= '<option value="' . $zone . '"' . $selected . '>' . 'GMT ' . date('P', $timestamp) . ' ' . $zone . '</option>';
        }
        echo $option;
    }

}

if (!function_exists('get_country_list')) {
    function get_country_list($old_data = '') {
        if ($old_data == '') {
            echo file_get_contents(app_path() . '/Helpers/country.txt');
        } else {
            $pattern      = '<option value="' . $old_data . '">';
            $replace      = '<option value="' . $old_data . '" selected="selected">';
            $country_list = file_get_contents(app_path() . '/Helpers/country.txt');
            $country_list = str_replace($pattern, $replace, $country_list);
            echo $country_list;
        }
    }
}

if (!function_exists('get_country_codes')) {
    function get_country_codes() {
        return json_decode(file_get_contents(app_path() . '/Helpers/country.json'), true);
    }
}

if (!function_exists('decimalPlace')) {
    function decimalPlace($number, $symbol = '', $format = '') {

        if ($symbol == '') {
            return money_format_2($number, $format);
        }

        if (get_currency_position() == 'right') {
            return money_format_2($number, $format) . $symbol;
        } else {
            return $symbol . money_format_2($number, $format);
        }

    }

}

/* Method use for Global amount only */
if (!function_exists('g_decimal_place')) {
    function g_decimal_place($number, $symbol = '', $format = '') {

        if ($symbol == '') {
            return money_format_2($number, $format);
        }

        if (get_currency_position() == 'left') {
            return $symbol . money_format_2($number, $format);
        } else {
            return money_format_2($number, $format) . $symbol;
        }

    }
}

if (!function_exists('money_format_2')) {
    function money_format_2($floatcurr, $curr = 'USD') {
        $currencies['ARS'] = array(2, ',', '.'); //  Argentine Peso
        $currencies['AMD'] = array(2, '.', ','); //  Armenian Dram
        $currencies['AWG'] = array(2, '.', ','); //  Aruban Guilder
        $currencies['AUD'] = array(2, '.', ' '); //  Australian Dollar
        $currencies['BSD'] = array(2, '.', ','); //  Bahamian Dollar
        $currencies['BHD'] = array(3, '.', ','); //  Bahraini Dinar
        $currencies['BDT'] = array(2, '.', ','); //  Bangladesh, Taka
        $currencies['BZD'] = array(2, '.', ','); //  Belize Dollar
        $currencies['BMD'] = array(2, '.', ','); //  Bermudian Dollar
        $currencies['BOB'] = array(2, '.', ','); //  Bolivia, Boliviano
        $currencies['BAM'] = array(2, '.', ','); //  Bosnia and Herzegovina, Convertible Marks
        $currencies['BWP'] = array(2, '.', ','); //  Botswana, Pula
        $currencies['BRL'] = array(2, ',', '.'); //  Brazilian Real
        $currencies['BND'] = array(2, '.', ','); //  Brunei Dollar
        $currencies['CAD'] = array(2, '.', ','); //  Canadian Dollar
        $currencies['KYD'] = array(2, '.', ','); //  Cayman Islands Dollar
        $currencies['CLP'] = array(0, '', '.'); //  Chilean Peso
        $currencies['CNY'] = array(2, '.', ','); //  China Yuan Renminbi
        $currencies['COP'] = array(2, ',', '.'); //  Colombian Peso
        $currencies['CRC'] = array(2, ',', '.'); //  Costa Rican Colon
        $currencies['HRK'] = array(2, ',', '.'); //  Croatian Kuna
        $currencies['CUC'] = array(2, '.', ','); //  Cuban Convertible Peso
        $currencies['CUP'] = array(2, '.', ','); //  Cuban Peso
        $currencies['CYP'] = array(2, '.', ','); //  Cyprus Pound
        $currencies['CZK'] = array(2, '.', ','); //  Czech Koruna
        $currencies['DKK'] = array(2, ',', '.'); //  Danish Krone
        $currencies['DOP'] = array(2, '.', ','); //  Dominican Peso
        $currencies['XCD'] = array(2, '.', ','); //  East Caribbean Dollar
        $currencies['EGP'] = array(2, '.', ','); //  Egyptian Pound
        $currencies['SVC'] = array(2, '.', ','); //  El Salvador Colon
        $currencies['ATS'] = array(2, ',', '.'); //  Euro
        $currencies['BEF'] = array(2, ',', '.'); //  Euro
        $currencies['DEM'] = array(2, ',', '.'); //  Euro
        $currencies['EEK'] = array(2, ',', '.'); //  Euro
        $currencies['ESP'] = array(2, ',', '.'); //  Euro
        $currencies['EUR'] = array(2, ',', '.'); //  Euro
        $currencies['FIM'] = array(2, ',', '.'); //  Euro
        $currencies['FRF'] = array(2, ',', '.'); //  Euro
        $currencies['GRD'] = array(2, ',', '.'); //  Euro
        $currencies['IEP'] = array(2, ',', '.'); //  Euro
        $currencies['ITL'] = array(2, ',', '.'); //  Euro
        $currencies['LUF'] = array(2, ',', '.'); //  Euro
        $currencies['NLG'] = array(2, ',', '.'); //  Euro
        $currencies['PTE'] = array(2, ',', '.'); //  Euro
        $currencies['GHC'] = array(2, '.', ','); //  Ghana, Cedi
        $currencies['GIP'] = array(2, '.', ','); //  Gibraltar Pound
        $currencies['GTQ'] = array(2, '.', ','); //  Guatemala, Quetzal
        $currencies['HNL'] = array(2, '.', ','); //  Honduras, Lempira
        $currencies['HKD'] = array(2, '.', ','); //  Hong Kong Dollar
        $currencies['HUF'] = array(0, '', '.'); //  Hungary, Forint
        $currencies['ISK'] = array(0, '', '.'); //  Iceland Krona
        $currencies['INR'] = array(2, '.', ','); //  Indian Rupee
        $currencies['IDR'] = array(2, ',', '.'); //  Indonesia, Rupiah
        $currencies['IRR'] = array(2, '.', ','); //  Iranian Rial
        $currencies['JMD'] = array(2, '.', ','); //  Jamaican Dollar
        $currencies['JPY'] = array(0, '', ','); //  Japan, Yen
        $currencies['JOD'] = array(3, '.', ','); //  Jordanian Dinar
        $currencies['KES'] = array(2, '.', ','); //  Kenyan Shilling
        $currencies['KWD'] = array(3, '.', ','); //  Kuwaiti Dinar
        $currencies['LVL'] = array(2, '.', ','); //  Latvian Lats
        $currencies['LBP'] = array(0, '', ' '); //  Lebanese Pound
        $currencies['LTL'] = array(2, ',', ' '); //  Lithuanian Litas
        $currencies['MKD'] = array(2, '.', ','); //  Macedonia, Denar
        $currencies['MYR'] = array(2, '.', ','); //  Malaysian Ringgit
        $currencies['MTL'] = array(2, '.', ','); //  Maltese Lira
        $currencies['MUR'] = array(0, '', ','); //  Mauritius Rupee
        $currencies['MXN'] = array(2, '.', ','); //  Mexican Peso
        $currencies['MZM'] = array(2, ',', '.'); //  Mozambique Metical
        $currencies['NPR'] = array(2, '.', ','); //  Nepalese Rupee
        $currencies['ANG'] = array(2, '.', ','); //  Netherlands Antillian Guilder
        $currencies['ILS'] = array(2, '.', ','); //  New Israeli Shekel
        $currencies['TRY'] = array(2, '.', ','); //  New Turkish Lira
        $currencies['NZD'] = array(2, '.', ','); //  New Zealand Dollar
        $currencies['NOK'] = array(2, ',', '.'); //  Norwegian Krone
        $currencies['PKR'] = array(2, '.', ','); //  Pakistan Rupee
        $currencies['PEN'] = array(2, '.', ','); //  Peru, Nuevo Sol
        $currencies['UYU'] = array(2, ',', '.'); //  Peso Uruguayo
        $currencies['PHP'] = array(2, '.', ','); //  Philippine Peso
        $currencies['PLN'] = array(2, '.', ' '); //  Poland, Zloty
        $currencies['GBP'] = array(2, '.', ','); //  Pound Sterling
        $currencies['OMR'] = array(3, '.', ','); //  Rial Omani
        $currencies['RON'] = array(2, ',', '.'); //  Romania, New Leu
        $currencies['ROL'] = array(2, ',', '.'); //  Romania, Old Leu
        $currencies['RUB'] = array(2, ',', '.'); //  Russian Ruble
        $currencies['SAR'] = array(2, '.', ','); //  Saudi Riyal
        $currencies['SGD'] = array(2, '.', ','); //  Singapore Dollar
        $currencies['SKK'] = array(2, ',', ' '); //  Slovak Koruna
        $currencies['SIT'] = array(2, ',', '.'); //  Slovenia, Tolar
        $currencies['ZAR'] = array(2, '.', ' '); //  South Africa, Rand
        $currencies['KRW'] = array(0, '', ','); //  South Korea, Won
        $currencies['SZL'] = array(2, '.', ', '); //  Swaziland, Lilangeni
        $currencies['SEK'] = array(2, ',', '.'); //  Swedish Krona
        $currencies['CHF'] = array(2, '.', '\''); //  Swiss Franc
        $currencies['TZS'] = array(2, '.', ','); //  Tanzanian Shilling
        $currencies['THB'] = array(2, '.', ','); //  Thailand, Baht
        $currencies['TOP'] = array(2, '.', ','); //  Tonga, Paanga
        $currencies['AED'] = array(2, '.', ','); //  UAE Dirham
        $currencies['UAH'] = array(2, ',', ' '); //  Ukraine, Hryvnia
        $currencies['USD'] = array(2, '.', ','); //  US Dollar
        $currencies['VUV'] = array(0, '', ','); //  Vanuatu, Vatu
        $currencies['VEF'] = array(2, ',', '.'); //  Venezuela Bolivares Fuertes
        $currencies['VEB'] = array(2, ',', '.'); //  Venezuela, Bolivar
        $currencies['VND'] = array(0, '', '.'); //  Viet Nam, Dong
        $currencies['ZWD'] = array(2, '.', ' '); //  Zimbabwe Dollar
        $currencies['XOF'] = array(2, '.', ' '); //  West African CFA franc

        if (array_key_exists($curr, $currencies)) {
            return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
        } else {
            return number_format($floatcurr, $currencies['USD'][0], $currencies['USD'][1], $currencies['USD'][2]);
        }

    }
}

if (!function_exists('formatinr')) {
    // custom function to generate: ##,##,###.##
    function formatinr($input) {
        $dec = "";
        $pos = strpos($input, ".");
        if ($pos === FALSE) {
            //no decimals
        } else {
            //decimals
            $dec   = substr(round(substr($input, $pos), 2), 1);
            $input = substr($input, 0, $pos);
        }
        $num   = substr($input, -3); // get the last 3 digits
        $input = substr($input, 0, -3); // omit the last 3 digits already stored in $num
        // loop the process - further get digits 2 by 2
        while (strlen($input) > 0) {
            $num   = substr($input, -2) . "," . $num;
            $input = substr($input, 0, -2);
        }
        return $num . $dec;
    }
}

if (!function_exists('load_language')) {
    function load_language($active = '') {
        $path    = resource_path() . "/language";
        $files   = scandir($path);
        $options = "";

        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            if ($name == "." || $name == "" || $name == "language") {
                continue;
            }

            $selected = "";
            if ($active == $name) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            $options .= "<option value='$name' $selected>" . $name . "</option>";

        }
        echo $options;
    }
}

if (!function_exists('get_language_list')) {
    function get_language_list() {
        $path  = resource_path() . "/language";
        $files = scandir($path);
        $array = array();

        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            if ($name == "." || $name == "" || $name == "language" || $name == "flags") {
                continue;
            }

            $array[] = $name;

        }
        return $array;
    }
}

if (!function_exists('process_string')) {

    function process_string($search_replace, $string) {
        $result = $string;
        foreach ($search_replace as $key => $value) {
            $result = str_replace($key, $value, $result);
        }
        return $result;
    }

}

if (!function_exists('permission_list')) {
    function permission_list() {

        $permission_list = \App\Models\AccessControl::where("role_id", Auth::user()->role_id)
            ->pluck('permission')->toArray();
        return $permission_list;
    }
}

if (!function_exists('get_currency_list')) {
    function get_currency_list($old_data = '', $serialize = false) {
        $currency_list = file_get_contents(app_path() . '/Helpers/currency.txt');

        if ($old_data == "") {
            echo $currency_list;
        } else {
            if ($serialize == true) {
                $old_data = unserialize($old_data);
                for ($i = 0; $i < count($old_data); $i++) {
                    $pattern       = '<option value="' . $old_data[$i] . '">';
                    $replace       = '<option value="' . $old_data[$i] . '" selected="selected">';
                    $currency_list = str_replace($pattern, $replace, $currency_list);
                }
                echo $currency_list;
            } else {
                $pattern       = '<option value="' . $old_data . '">';
                $replace       = '<option value="' . $old_data . '" selected="selected">';
                $currency_list = str_replace($pattern, $replace, $currency_list);
                echo $currency_list;
            }
        }
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol($currency_code) {
        include app_path() . '/Helpers/currency_symbol.php';

        if (array_key_exists($currency_code, $currency_symbols)) {
            return $currency_symbols[$currency_code];
        }
        return $currency_code;

    }
}

if (!function_exists('status')) {
    function status($status) {
        if ($status == 1) {
            return "<span class='badge badge-success'>" . _lang('Active') . "</span>";
        } else if ($status == 0) {
            return "<span class='badge badge-danger'>" . _lang('Deactivated') . "</span>";
        }
    }
}

if (!function_exists('transaction_status')) {
    function transaction_status($status) {
        if ($status == 2) {
            return "<span class='badge badge-success'>" . _lang('Completed') . "</span>";
        } else if ($status == 1) {
            return "<span class='badge badge-warning'>" . _lang('Pending') . "</span>";
        } else if ($status == 0) {
            return "<span class='badge badge-danger'>" . _lang('Cancelled') . "</span>";
        }
    }
}

if (!function_exists('fdr_status')) {
    function fdr_status($status) {
        if ($status == 3) {
            return "<span class='badge badge-primary'>" . _lang('Completde') . "</span>";
        } else if ($status == 2) {
            return "<span class='badge badge-danger'>" . _lang('Cancelled') . "</span>";
        } else if ($status == 1) {
            return "<span class='badge badge-success'>" . _lang('Active') . "</span>";
        } else if ($status == 0) {
            return "<span class='badge badge-warning'>" . _lang('Pending') . "</span>";
        }
    }
}

if (!function_exists('ticket_status')) {
    function ticket_status($status) {
        if ($status == 2) {
            return "<span class='badge badge-success'>" . _lang('Closed') . "</span>";
        } else if ($status == 1) {
            return "<span class='badge badge-primary'>" . _lang('Open') . "</span>";
        } else if ($status == 0) {
            return "<span class='badge badge-danger'>" . _lang('Pending') . "</span>";
        }
    }
}

if (!function_exists('show_status')) {
    function show_status($value, $status) {
        return "<span class='badge badge-$status'>" . $value . "</span>";
    }
}

if (!function_exists('generate_gift_card')) {
    function generate_gift_card($length = '16') {
        $code = substr(str_shuffle(str_repeat('01F23LP45FSMQ678QZ9', $length)), 0, $length);
        $code = implode("-", str_split($code, 4));

        $db = DB::select("SELECT code FROM gift_cards WHERE code = '$code'");

        if ($db) {
            generate_gift_card();
        }

        return $code;
    }
}

if (!function_exists('get_account_balance')) {

    function get_account_balance($currency_id, $user_id = '') {
        if ($user_id == '') {
            $user_id = Auth::user()->id;
        }

        $result = DB::select("SELECT ((SELECT IFNULL(SUM(amount),0) FROM transactions WHERE dr_cr = 'cr'
	   AND user_id = $user_id AND currency_id = $currency_id AND status=2) - (SELECT IFNULL(SUM(amount),0) FROM transactions
	   WHERE dr_cr = 'dr' AND user_id = $user_id AND currency_id = $currency_id AND status != 0)) as balance");
        return $result[0]->balance;

    }

}

if (!function_exists('file_icon')) {
    function file_icon($mime_type) {
        static $font_awesome_file_icon_classes = [
            // Images
            'image'                                                                     => 'fa-file-image',
            // Audio
            'audio'                                                                     => 'fa-file-audio',
            // Video
            'video'                                                                     => 'fa-file-video',
            // Documents
            'application/pdf'                                                           => 'fa-file-pdf',
            'application/msword'                                                        => 'fa-file-word',
            'application/vnd.ms-word'                                                   => 'fa-file-word',
            'application/vnd.oasis.opendocument.text'                                   => 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml'            => 'fa-file-word',
            'application/vnd.ms-excel'                                                  => 'fa-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml'               => 'fa-file-excel',
            'application/vnd.oasis.opendocument.spreadsheet'                            => 'fa-file-excel',
            'application/vnd.ms-powerpoint'                                             => 'fa-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml'              => 'ffa-file-powerpoint',
            'application/vnd.oasis.opendocument.presentation'                           => 'fa-file-powerpoint',
            'text/plain'                                                                => 'fa-file-alt',
            'text/html'                                                                 => 'fa-file-code',
            'application/json'                                                          => 'fa-file-code',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'fa-file-excel',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fa-file-powerpoint',
            // Archives
            'application/gzip'                                                          => 'fa-file-archive',
            'application/zip'                                                           => 'fa-file-archive',
            'application/x-zip-compressed'                                              => 'fa-file-archive',
            // Misc
            'application/octet-stream'                                                  => 'fa-file-archive',
        ];

        if (isset($font_awesome_file_icon_classes[$mime_type])) {
            return $font_awesome_file_icon_classes[$mime_type];
        }

        $mime_group = explode('/', $mime_type, 2)[0];
        return (isset($font_awesome_file_icon_classes[$mime_group])) ? $font_awesome_file_icon_classes[$mime_group] : 'fa-file';
    }
}

if (!function_exists('update_currency_exchange_rate')) {
    function update_currency_exchange_rate() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));

        $start = new \Carbon\Carbon(get_option('currency_update_time', date("Y-m-d H:i:s", strtotime('-24 hours', time()))));
        $end   = \Carbon\Carbon::now();

        $last_run = $start->diffInHours($end);

        if ($last_run >= 12) {
            // Set API Endpoint and API key
            $endpoint   = 'latest';
            $access_key = get_option('fixer_api_key');

            // Initialize CURL:
            $ch = curl_init('http://data.fixer.io/api/' . $endpoint . '?access_key=' . $access_key . '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Store the data:
            $json = curl_exec($ch);
            curl_close($ch);

            // Decode JSON response:
            $exchangeRates = json_decode($json, true);

            if ($exchangeRates['success'] == false) {
                return false;
            }

            $base_currency = $exchangeRates['base'];

            $currency_rates = array();

            foreach ($exchangeRates['rates'] as $currency => $rate) {
                $currency_rates[] = array(
                    "currency"   => $currency,
                    "rate"       => $rate,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                );
                //echo $currency." - ".$rate."<br>";
            }

            DB::beginTransaction();

            \App\Models\CurrencyRate::getQuery()->delete();

            DB::statement("ALTER TABLE currency_rates AUTO_INCREMENT = 1");

            \App\Models\CurrencyRate::insert($currency_rates);

            //Store Last Update time
            update_option("currency_update_time", \Carbon\Carbon::now());

            DB::commit();
        }
    }
}

if (!function_exists('convert_currency')) {
    function convert_currency($from, $to, $amount) {
        $currency1 = \App\Models\Currency::find($from)->exchange_rate;
        $currency2 = \App\Models\Currency::find($to)->exchange_rate;

        $converted_output = ($amount / $currency1) * $currency2;
        return $converted_output;
    }
}

if (!function_exists('convert_currency_2')) {
    function convert_currency_2($currency1_rate, $currency2_rate, $amount) {
        $currency1 = $currency1_rate;
        $currency2 = $currency2_rate;

        $converted_output = ($amount / $currency1) * $currency2;
        return $converted_output;
    }
}

if (!function_exists('xss_clean')) {
    function xss_clean($data) {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data     = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        return $data;
    }
}

// convert seconds into time
if (!function_exists('time_from_seconds')) {
    function time_from_seconds($seconds) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds - ($h * 3600) - ($m * 60);
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
}

//Request Count
if (!function_exists('request_count')) {
    function request_count($request, $html = false, $class = "sidebar-notification-count") {
        if ($request == 'pending_tickets') {
            $notification_count = \App\Models\SupportTicket::where('status', 0)->count();
        } else if ($request == 'pending_loans') {
            $notification_count = \App\Models\Loan::where('status', 0)->count();
        } else if ($request == 'deposit_requests') {
            $notification_count = \App\Models\DepositRequest::where('status', 1)->count();
        } else if ($request == 'withdraw_requests') {
            $notification_count = \App\Models\WithdrawRequest::where('status', 1)->count();
        } else if ($request == 'wire_transfer_requests') {
            $notification_count = \App\Models\Transaction::where('type', 'Wire_Transfer')->where('status', 1)->count();
        } else if ($request == 'fdr_requests') {
            $notification_count = \App\Models\FixedDeposit::where('status', 0)->count();
        }

        if ($html == false) {
            return $notification_count;
        }

        if ($notification_count > 0) {
            return '<span class="' . $class . '">' . $notification_count . '</span>';
        }

    }
}

/* Intelligent Functions */
if (!function_exists('get_language')) {
    function get_language($force = false) {

        $language = $force == false ? session('language') : '';

        if ($language == '') {
            $language = Cache::get('language');
        }

        if ($language == '') {
            $language = get_option('language');
            if ($language == '') {
                \Cache::put('language', 'language');
            } else {
                \Cache::put('language', $language);
            }

        }

        return $language;
    }
}

if (!function_exists('get_currency_position')) {
    function get_currency_position() {
        $currency_position = Cache::get('currency_position');

        if ($currency_position == '') {
            $currency_position = get_option('currency_position');
            \Cache::put('currency_position', $currency_position);
        }

        return $currency_position;
    }
}

if (!function_exists('get_base_currency')) {
    function base_currency_id() {
        $base_currency_id = Cache::get('base_currency_id');

        if ($base_currency_id == '') {
            $currency         = \App\Models\Currency::where("base_currency", 1)->first();
            $base_currency_id = $currency->id;
            \Cache::put('base_currency_id', $base_currency_id);
        }

        if (!$base_currency_id) {
            $currency         = \App\Models\Currency::all()->first();
            $base_currency_id = $currency->id;
            \Cache::put('base_currency_id', $base_currency_id);
        }
        return $base_currency_id;
    }
}

if (!function_exists('get_base_currency')) {
    function get_base_currency() {
        $base_currency = Cache::get('base_currency');

        if ($base_currency == '') {
            $currency      = \App\Models\Currency::where("base_currency", 1)->first();
            $base_currency = $currency->name;
            \Cache::put('base_currency', $base_currency);
        }

        if (!$base_currency) {
            $currency      = \App\Models\Currency::all()->first();
            $base_currency = $currency->name;
            \Cache::put('base_currency', $base_currency);
        }
        return $base_currency;
    }
}

if (!function_exists('currency')) {
    function currency($currency = '') {
        if ($currency == '') {
            $currency = get_base_currency();
        }
        return html_entity_decode(get_currency_symbol($currency), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('get_date_format')) {
    function get_date_format() {
        $date_format = Cache::get('date_format');

        if ($date_format == '') {
            $date_format = get_option('date_format');
            \Cache::put('date_format', $date_format);
        }

        return $date_format;
    }
}

if (!function_exists('get_time_format')) {
    function get_time_format() {
        $time_format = Cache::get('time_format');

        if ($time_format == '') {
            $time_format = get_option('time_format');
            \Cache::put('time_format', $time_format);
        }

        $time_format = $time_format == 24 ? 'H:i' : 'h:i A';

        return $time_format;
    }
}
