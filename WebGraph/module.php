<?

class WebGraph extends IPSModule {

    public function Create() {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyString("Username", "");
        $this->RegisterPropertyString("Password", "");

        $this->RegisterPropertyString("AccessList", "[]");
        $this->RegisterPropertyInteger("BackgroundColorHexColor", 0);
        $this->RegisterPropertyInteger("BackgroundColor", 0);
    }

    public function ApplyChanges() {
        //Never delete this line!
        parent::ApplyChanges();

        $this->RegisterHook("/hook/webgraph");
    }

    private function TranslateChart($chart) {

        $weekdays = Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
        $months = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        $merged = array_merge($weekdays, $months);
        foreach ($merged as $str) {
            $chart = str_replace($str, $this->Translate($str), $chart);
        }

        return $chart;

    }

    private function IsAllowedObject($id) {

        $allowed = json_decode($this->ReadPropertyString("AccessList"));

        foreach($allowed as $item) {
            if($item->ObjectID == $id) {
                return true;
            }
        }

        return false;

    }

    private function BuildCSSForMultiChart($mediaID) {

        $content = json_decode(base64_decode(IPS_GetMediaContent($mediaID)));

        $css = "/* Additional CSS for multi chart colorizing */" . PHP_EOL;
        $i = 1;
        foreach($content->datasets as $dataset) {
            if($dataset->fillColor == "clear") {
                $dataset->fillColor = "transparent";
            }
            $css .= 'div.ipsChart > svg > g.graphs > g.background > g:nth-of-type(' . $i . ') path {' . PHP_EOL . '    fill: ' . $dataset->fillColor . ';' . PHP_EOL . '    opacity: 0.5; }' . PHP_EOL;
            $css .= 'div.ipsChart > svg > g.graphs > g.outline > g:nth-of-type(' . $i . ') path {' . PHP_EOL . '    stroke: ' . $dataset->strokeColor . '; }' . PHP_EOL;
            $i++;
        }

        return $css;

    }

    private function BuildLegendForMultiChart($mediaID) {

        $content = json_decode(base64_decode(IPS_GetMediaContent($mediaID)));

        $legend = '<div style="float: clear"></div><div style="float: left; margin-right: 10px">Name: </div>';
        foreach($content->datasets as $dataset) {
            $legend .= '<div style="width: 16px; height: 16px; background: ' . $dataset->fillColor . '; border: 1px solid ' . $dataset->strokeColor . '; float: left; margin-right: 5px;"></div><div style="float: left; margin-right: 10px">' . IPS_GetName($dataset->variableID) . '</div>' . PHP_EOL;
        }
        return $legend;

    }

    private function RegisterHook($WebHook) {
        $ids = IPS_GetInstanceListByModuleID("{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}");
        if(sizeof($ids) > 0) {
            $hooks = json_decode(IPS_GetProperty($ids[0], "Hooks"), true);
            $found = false;
            foreach($hooks as $index => $hook) {
                if($hook['Hook'] == $WebHook) {
                    if($hook['TargetID'] == $this->InstanceID)
                        return;
                    $hooks[$index]['TargetID'] = $this->InstanceID;
                    $found = true;
                }
            }
            if(!$found) {
                $hooks[] = Array("Hook" => $WebHook, "TargetID" => $this->InstanceID);
            }
            IPS_SetProperty($ids[0], "Hooks", json_encode($hooks));
            IPS_ApplyChanges($ids[0]);
        }
    }

    protected function GetBackgroundColor($backgroundColor)
    {
        $hex = strpos($backgroundColor, "hex");
        if ($hex === false)
        {
            $backgroundcolor = $backgroundColor;
        }
        else
        {
            $backgroundcolor = "#".substr($backgroundColor, 3, 6);
        }
        return $backgroundcolor;
    }

    protected function GetBackgroundColorName($color)
    {
        $colorlist = array(
            0 => "transparent",
            1 => "AliceBlue",
            2 => "AntiqueWhite",
            3 => "Aqua",
            4 => "Aquamarine",
            5 => "Azure",
            6 => "Beige",
            7 => "Bisque",
            8 => "Black",
            9 => "BlanchedAlmond",
            10 => "Blue",
            11 => "BlueViolet",
            12 => "Brown",
            13 => "BurlyWood",
            14 => "CadetBlue",
            15 => "Chartreuse",
            16 => "Chocolate",
            17 => "Coral",
            18 => "CornflowerBlue",
            19 => "Cornsilk",
            20 => "Crimson",
            21 => "Cyan",
            22 => "DarkBlue",
            23 => "DarkCyan",
            24 => "DarkGoldenRod",
            25 => "DarkGray",
            26 => "DarkGreen",
            27 => "DarkKhaki",
            28 => "DarkMagenta",
            29 => "DarkOliveGreen",
            30 => "DarkOrange",
            31 => "DarkOrchid",
            32 => "DarkRed",
            33 => "DarkSalmon",
            34 => "DarkSeaGreen",
            35 => "DarkSlateBlue",
            36 => "DarkSlateGray",
            37 => "DarkTurquoise",
            38 => "DarkViolet",
            39 => "DeepPink",
            40 => "DeepSkyBlue",
            41 => "DimGray",
            42 => "DodgerBlue",
            43 => "FireBrick",
            44 => "FloralWhite",
            45 => "ForestGreen",
            46 => "Fuchsia",
            47 => "Gainsboro",
            48 => "GhostWhite",
            49 => "Gold",
            50 => "GoldenRod",
            51 => "Gray",
            52 => "Grey",
            53 => "Green",
            54 => "GreenYellow",
            55 => "HoneyDew",
            56 => "HotPink",
            57 => "IndianRed",
            58 => "Indigo",
            59 => "Ivory",
            60 => "Khaki",
            61 => "Lavender",
            62 => "LavenderBlush",
            63 => "LawnGreen",
            64 => "LemonChiffon",
            65 => "LightBlue",
            66 => "LightCoral",
            67 => "LightCyan",
            68 => "LightGoldenRodYellow",
            69 => "LightGray",
            70 => "LightGrey",
            71 => "LightGreen",
            72 => "LightPink",
            73 => "LightSalmon",
            74 => "LightSeaGreen",
            75 => "LightSkyBlue",
            76 => "LightSlateGray",
            77 => "LightSlateGrey",
            78 => "LightSteelBlue",
            79 => "LightYellow",
            80 => "Lime",
            81 => "LimeGreen",
            82 => "Linen",
            83 => "Magenta",
            84 => "Maroon",
            85 => "MediumAquaMarine",
            86 => "MediumBlue",
            87 => "MediumOrchid",
            88 => "MediumPurple",
            89 => "MediumSeaGreen",
            90 => "MediumSlateBlue",
            91 => "MediumSpringGreen",
            92 => "MediumTurquoise",
            93 => "MediumVioletRed",
            94 => "MidnightBlue",
            95 => "MintCream",
            96 => "MistyRose",
            97 => "Moccasin",
            98 => "NavajoWhite",
            99 => "Navy",
            100	=> "OldLace",
            101	=> "Olive",
            102	=> "OliveDrab",
            103	=> "Orange",
            104	=> "OrangeRed",
            105	=> "Orchid",
            106	=> "PaleGoldenRod",
            107	=> "PaleGreen",
            108	=> "PaleTurquoise",
            109	=> "PaleVioletRed",
            110	=> "PapayaWhip",
            111	=> "PeachPuff",
            112	=> "Peru",
            113	=> "Pink",
            114	=> "Plum",
            115	=> "PowderBlue",
            116	=> "Purple",
            117	=> "RebeccaPurple",
            118	=> "Red",
            119	=> "RosyBrown",
            120	=> "RoyalBlue",
            121	=> "SaddleBrown",
            122	=> "Salmon",
            123	=> "SandyBrown",
            124	=> "SeaGreen",
            125	=> "SeaShell",
            126	=> "Sienna",
            127	=> "Silver",
            128	=> "SkyBlue",
            129	=> "SlateBlue",
            130	=> "SlateGray",
            131	=> "SlateGrey",
            132	=> "Snow",
            133	=> "SpringGreen",
            134	=> "SteelBlue",
            135	=> "Tan",
            136	=> "Teal",
            137	=> "Thistle",
            138	=> "Tomato",
            139	=> "Turquoise",
            140	=> "Violet",
            141	=> "Wheat",
            142	=> "White",
            143	=> "WhiteSmoke",
            144	=> "Yellow",
            145	=> "YellowGreen"
        );

        if (array_key_exists($color, $colorlist))
        {
            $colorname = $colorlist[$color];
        }
        else
        {
            $colorname = "Black";
        }
        return $colorname;
    }

    protected function GetHexColor($hexcolor_int)
    {
        $hexcolor = strtoupper(dechex($hexcolor_int));
        if($hexcolor == "0")
        {
            $hexstring = "000000";

        }
        else
        {
            $hexstring = $hexcolor;
        }
        return $hexstring;
    }

    //Configuration Form
    public function GetConfigurationForm()
    {
        $formhead = $this->FormHead();
        $formactions = $this->FormActions();
        $formelementsend = '{ "type": "Label", "label": "__________________________________________________________________________________________________" }';
        $formstatus = $this->FormStatus();
        return	'{ '.$formhead.$formelementsend.'],'.$formactions.$formstatus.' }';
    }

    protected function FormHead()
    {
        $allowed = json_decode($this->ReadPropertyString("AccessList"));

        $values = Array();
        foreach ($allowed as $item) {
            $values[] = Array("Name" => IPS_GetName($item->ObjectID));
        }
        $values = json_encode($values);
        $form = '"elements":
	[
		{ "type": "Label", "label": "Please add all allowed object IDs" },
		{
			"type": "List",
			"name": "AccessList",
			"caption": "Access List",
			"rowCount": 5,
			"add": true,
			"delete": true,
			"sort": {
				"column": "Name",
				"direction": "ascending"
			},
			"columns": [{
				"label": "ObjectID",
				"name": "ObjectID",
				"width": "100px",
				"add": 0,
				"edit": {
					"type": "SelectObject"
				}
			}, {
				"label": "Name",
				"name": "Name",
				"width": "auto",
				"add": ""
			}],
			"values": '.$values.'
		},
		{ "type": "Label", "label": "Expert only: Require username/password to load the graphs" },
		{ "name": "Username", "type": "ValidationTextBox", "caption": "Username" },
		{ "name": "Password", "type": "PasswordTextBox", "caption": "Password" },
		{ "type": "Label", "label": "Set the background color for the graph, e.g. transparent or black or green" },
		{ "type": "SelectColor", "name": "BackgroundColorHexColor", "caption": "Background color" },
		{ "type": "Select", "name": "BackgroundColor", "caption": "Background color",
			"options": [
				{ "label": "transparent", "value": 0 },
				{ "label": "AliceBlue", "value": 1 },
				{ "label": "AntiqueWhite", "value": 2 },
				{ "label": "Aqua", "value": 3 },
				{ "label": "Aquamarine", "value": 4 },
				{ "label": "Azure", "value": 5 },
				{ "label": "Beige", "value": 6 },
				{ "label": "Bisque", "value": 7 },
				{ "label": "Black", "value": 8 },
				{ "label": "BlanchedAlmond", "value": 9 },
				{ "label": "Blue", "value": 10 },
				{ "label": "BlueViolet", "value": 11 },
				{ "label": "Brown", "value": 12 },
				{ "label": "BurlyWood", "value": 13 },
				{ "label": "CadetBlue", "value": 14 },
				{ "label": "Chartreuse", "value": 15 },
				{ "label": "Chocolate", "value": 16 },
				{ "label": "Coral", "value": 17 },
				{ "label": "CornflowerBlue", "value": 18 },
				{ "label": "Cornsilk", "value": 19 },
				{ "label": "Crimson", "value": 20 },
				{ "label": "Cyan", "value": 21 },
				{ "label": "DarkBlue", "value": 22 },
				{ "label": "DarkCyan", "value": 23 },
				{ "label": "DarkGoldenRod", "value": 24 },
				{ "label": "DarkGray", "value": 25 },
				{ "label": "DarkGreen", "value": 26 },
				{ "label": "DarkKhaki", "value": 27 },
				{ "label": "DarkMagenta", "value": 28 },
				{ "label": "DarkOliveGreen", "value": 29 },
				{ "label": "DarkOrange", "value": 30 },
				{ "label": "DarkOrchid", "value": 31 },
				{ "label": "DarkRed", "value": 32 },
				{ "label": "DarkSalmon", "value": 33 },
				{ "label": "DarkSeaGreen", "value": 34 },
				{ "label": "DarkSlateBlue", "value": 35 },
				{ "label": "DarkSlateGray", "value": 36 },
				{ "label": "DarkTurquoise", "value": 37 },
				{ "label": "DarkViolet", "value": 38 },
				{ "label": "DeepPink", "value": 39 },
				{ "label": "DeepSkyBlue", "value": 40 },
				{ "label": "DimGray", "value": 41 },
				{ "label": "DodgerBlue", "value": 42 },
				{ "label": "FireBrick", "value": 43 },
				{ "label": "FloralWhite", "value": 44 },
				{ "label": "ForestGreen", "value": 45 },
				{ "label": "Fuchsia", "value": 46 },
				{ "label": "Gainsboro", "value": 47 },
				{ "label": "GhostWhite", "value": 48 },
				{ "label": "Gold", "value": 49 },
				{ "label": "GoldenRod", "value": 50 },
				{ "label": "Gray", "value": 51 },
				{ "label": "Grey", "value": 52 },
				{ "label": "Green", "value": 53 },
				{ "label": "GreenYellow", "value": 54 },
				{ "label": "HoneyDew", "value": 55 },
				{ "label": "HotPink", "value": 56 },
				{ "label": "IndianRed", "value": 57 },
				{ "label": "Indigo", "value": 58 },
				{ "label": "Ivory", "value": 59 },
				{ "label": "Khaki", "value": 60 },
				{ "label": "Lavender", "value": 61 },
				{ "label": "LavenderBlush", "value": 62 },
				{ "label": "LawnGreen", "value": 63 },
				{ "label": "LemonChiffon", "value": 64 },
				{ "label": "LightBlue", "value": 65 },
				{ "label": "LightCoral", "value": 66 },
				{ "label": "LightCyan", "value": 67 },
				{ "label": "LightGoldenRodYellow", "value": 68 },
				{ "label": "LightGray", "value": 69 },
				{ "label": "LightGrey", "value": 70 },
				{ "label": "LightGreen", "value": 71 },
				{ "label": "LightPink", "value": 72 },
				{ "label": "LightSalmon", "value": 73 },
				{ "label": "LightSeaGreen", "value": 74 },
				{ "label": "LightSkyBlue", "value": 75 },
				{ "label": "LightSlateGray", "value": 76 },
				{ "label": "LightSlateGrey", "value": 77 },
				{ "label": "LightSteelBlue", "value": 78 },
				{ "label": "LightYellow", "value": 79 },
				{ "label": "Lime", "value": 80 },
				{ "label": "LimeGreen", "value": 81 },
				{ "label": "Linen", "value": 82 },
				{ "label": "Magenta", "value": 83 },
				{ "label": "Maroon", "value": 84 },
				{ "label": "MediumAquaMarine", "value": 85 },
				{ "label": "MediumBlue", "value": 86 },
				{ "label": "MediumOrchid", "value": 87 },
				{ "label": "MediumPurple", "value": 88 },
				{ "label": "MediumSeaGreen", "value": 89 },
				{ "label": "MediumSlateBlue", "value": 90 },
				{ "label": "MediumSpringGreen", "value": 91 },
				{ "label": "MediumTurquoise", "value": 92 },
				{ "label": "MediumVioletRed", "value": 93 },
				{ "label": "MidnightBlue", "value": 94 },
				{ "label": "MintCream", "value": 95 },
				{ "label": "MistyRose", "value": 96 },
				{ "label": "Moccasin", "value": 97 },
				{ "label": "NavajoWhite", "value": 98 },
				{ "label": "Navy", "value": 99 },
				{ "label": "OldLace", "value": 100 },
				{ "label": "Olive", "value": 101 },
				{ "label": "OliveDrab", "value": 102 },
				{ "label": "Orange", "value": 103 },
				{ "label": "OrangeRed", "value": 104 },
				{ "label": "Orchid", "value": 105 },
				{ "label": "PaleGoldenRod", "value": 106 },
				{ "label": "PaleGreen", "value": 107 },
				{ "label": "PaleTurquoise", "value": 108 },
				{ "label": "PaleVioletRed", "value": 109 },
				{ "label": "PapayaWhip", "value": 110 },
				{ "label": "PeachPuff", "value": 111 },
				{ "label": "Peru", "value": 112 },
				{ "label": "Pink", "value": 113 },
				{ "label": "Plum", "value": 114 },
				{ "label": "PowderBlue", "value": 115 },
				{ "label": "Purple", "value": 116 },
				{ "label": "RebeccaPurple", "value": 117 },
				{ "label": "Red", "value": 118 },
				{ "label": "RosyBrown", "value": 119 },
				{ "label": "RoyalBlue", "value": 120 },
				{ "label": "SaddleBrown", "value": 121 },
				{ "label": "Salmon", "value": 122 },
				{ "label": "SandyBrown", "value": 123 },
				{ "label": "SeaGreen", "value": 124 },
				{ "label": "SeaShell", "value": 125 },
				{ "label": "Sienna", "value": 126 },
				{ "label": "Silver", "value": 127 },
				{ "label": "SkyBlue", "value": 128 },
				{ "label": "SlateBlue", "value": 129 },
				{ "label": "SlateGray", "value": 130 },
				{ "label": "SlateGrey", "value": 131 },
				{ "label": "Snow", "value": 132 },
				{ "label": "SpringGreen", "value": 133 },
				{ "label": "SteelBlue", "value": 134 },
				{ "label": "Tan", "value": 135 },
				{ "label": "Teal", "value": 136 },
				{ "label": "Thistle", "value": 137 },
				{ "label": "Tomato", "value": 138 },
				{ "label": "Turquoise", "value": 139 },
				{ "label": "Violet", "value": 140 },
				{ "label": "Wheat", "value": 141 },
				{ "label": "White", "value": 142 },
				{ "label": "WhiteSmoke", "value": 143 },
				{ "label": "Yellow", "value": 144 },
				{ "label": "YellowGreen", "value": 145 }
			]
		},';
        return $form;
    }

    protected function FormActions()
    {
        $allowed = json_decode($this->ReadPropertyString("AccessList"));
        $options = Array();

        foreach ($allowed as $item) {
            $options[] = Array("label" => IPS_GetName($item->ObjectID), "value" => $item->ObjectID);
        }
        $options = json_encode($options);
        $form = '"actions":
	[
		{ "type": "Select", "name": "ObjectID",	"caption": "ObjectID", "options": '.$options.' },
		{ "type": "ValidationTextBox", "name": "StartTime", "caption": "Date/Time" },
		{ "type": "Select", "name": "TimeSpan",	"caption": "Time span",
			"options": [{
				"label": "Hour",
				"value": 0
			}, {
				"label": "Day",
				"value": 1
			}, {
				"label": "Week",
				"value": 2
			}, {
				"label": "Month",
				"value": 3
			}, {
				"label": "Year",
				"value": 4
			}, {
				"label": "Decade",
				"value": 5
			}]
		},
		{ "type": "CheckBox", "name": "IsHighDensity", "caption": "HighDensity" },
		{ "type": "CheckBox", "name": "IsExtrema", "caption": "Extrema" },
		{ "type": "CheckBox", "name": "IsDynamic", "caption": "DynamicScaling" },
		{ "type": "CheckBox", "name": "IsContinuous", "caption": "Continuous" },
		{ "type": "NumberSpinner", "name": "Width", "caption": "Width" },
		{ "type": "NumberSpinner", "name": "Height", "caption": "Height" },
		{ "type": "CheckBox", "name": "ShowTitle", "caption": "Show title" },
		{ "type": "CheckBox", "name": "ShowLegend", "caption": "Show legend" },
		{ "type": "Label", "label": "Use background color text (select the color in the drop down field):" },
		';
        $color = $this->ReadPropertyInteger("BackgroundColor");
        $BackgroundColor = $this->GetBackgroundColorName($color);
        $hexcolor_value = $this->ReadPropertyInteger("BackgroundColorHexColor");
        $BackgroundColorHexColor = $this->GetHexColor($hexcolor_value);
        $form .= ' 
		{ "type": "Button", "label": "Open (Localhost)", "onClick": "echo \"http://127.0.0.1:3777/hook/webgraph/?id=$ObjectID&startTime=$StartTime&timeSpan=$TimeSpan&isHighDensity=$IsHighDensity&isExtrema=$IsExtrema&isDynamic=$IsDynamic&isContinuous=$IsContinuous&width=$Width&height=$Height&showTitle=$ShowTitle&showLegend=$ShowLegend&backgroundColor='.$BackgroundColor.'\"" },
		{ "type": "Button", "label": "Open (Symcon Connect)", "onClick": "$url = CC_GetURL(IPS_GetInstanceListByModuleID(\'{9486D575-BE8C-4ED8-B5B5-20930E26DE6F}\')[0]); if($url == \'\') die((new IPSModule($id))->Translate(\'Symcon Connect is not active!\')); echo \"$url/hook/webgraph/?id=$ObjectID&startTime=$StartTime&timeSpan=$TimeSpan&isHighDensity=$IsHighDensity&isExtrema=$IsExtrema&isDynamic=$IsDynamic&isContinuous=$IsContinuous&width=$Width&height=$Height&showTitle=$ShowTitle&showLegend=$ShowLegend&backgroundColor='.$BackgroundColor.'\"" },
		{ "type": "Label", "label": "Use background hexcolor (select the hexcolor in the colorpicker):" },
		{ "type": "Button", "label": "Open (Localhost)", "onClick": "echo \"http://127.0.0.1:3777/hook/webgraph/?id=$ObjectID&startTime=$StartTime&timeSpan=$TimeSpan&isHighDensity=$IsHighDensity&isExtrema=$IsExtrema&isDynamic=$IsDynamic&isContinuous=$IsContinuous&width=$Width&height=$Height&showTitle=$ShowTitle&showLegend=$ShowLegend&backgroundColor=hex'.$BackgroundColorHexColor.'\"" },
		{ "type": "Button", "label": "Open (Symcon Connect)", "onClick": "$url = CC_GetURL(IPS_GetInstanceListByModuleID(\'{9486D575-BE8C-4ED8-B5B5-20930E26DE6F}\')[0]); if($url == \'\') die((new IPSModule($id))->Translate(\'Symcon Connect is not active!\')); echo \"$url/hook/webgraph/?id=$ObjectID&startTime=$StartTime&timeSpan=$TimeSpan&isHighDensity=$IsHighDensity&isExtrema=$IsExtrema&isDynamic=$IsDynamic&isContinuous=$IsContinuous&width=$Width&height=$Height&showTitle=$ShowTitle&showLegend=$ShowLegend&backgroundColor=hex'.$BackgroundColorHexColor.'\"" }
	],';
        return  $form;
    }

    protected function FormStatus()
    {
        $form = '"status":
            [
                {
                    "code": 101,
                    "icon": "inactive",
                    "caption": "Creating instance."
                },
				{
                    "code": 102,
                    "icon": "active",
                    "caption": "Webgraph active."
                },
                {
                    "code": 104,
                    "icon": "inactive",
                    "caption": "interface closed."
                }
            ]';
        return $form;
    }

    /**
     * This function will be called by the hook control. Visibility should be protected!
     */
    protected function ProcessHookData() {

        if($_IPS['SENDER'] == "Execute") {
            echo "This script cannot be used this way.";
            return;
        }

        if((IPS_GetProperty($this->InstanceID, "Username") != "") || (IPS_GetProperty($this->InstanceID, "Password") != "")) {
            if(!isset($_SERVER['PHP_AUTH_USER']))
                $_SERVER['PHP_AUTH_USER'] = "";
            if(!isset($_SERVER['PHP_AUTH_PW']))
                $_SERVER['PHP_AUTH_PW'] = "";

            if(($_SERVER['PHP_AUTH_USER'] != IPS_GetProperty($this->InstanceID, "Username")) || ($_SERVER['PHP_AUTH_PW'] != IPS_GetProperty($this->InstanceID, "Password"))) {
                header('WWW-Authenticate: Basic Realm="Geofency WebHook"');
                header('HTTP/1.0 401 Unauthorized');
                echo "Authorization required";
                return;
            }
        }

        if(!isset($_GET['id'])) {
            die("Missing parameter: id");
        }
        else
        {
            $this->SendDebug("Web Graph", "GET ".json_encode($_GET),0);
        }

        $id = intval($_GET['id']);

        if(!$this->IsAllowedObject($id)) {
            echo "This id is not allowed";
            return;
        }

        if(!IPS_VariableExists($id) && !IPS_MediaExists($id)) {
            echo "Invalid VariableID/MediaID";
            return;
        }

        $startTime = time();
        if(isset($_GET['startTime']) && $_GET['startTime'] != "") {
            $startTime = strtotime($_GET['startTime']);
        }

        /*
         * 0 = Hour
         * 1 = Day
         * 2 = Week
         * 3 = Month
         * 4 = Year
         * 5 = Decade
         *
         */
        $timeSpan = 4;
        if(isset($_GET['timeSpan']))
        {
            $this->SendDebug("Web Graph", "timeSpan: ".$_GET['timeSpan'],0);
            $timeSpan = intval($_GET['timeSpan']);
        }

        $isHighDensity = false;
        if(isset($_GET['isHighDensity']))
        {
            $this->SendDebug("Web Graph", "isHighDensity: ".$_GET['isHighDensity'],0);
            $isHighDensity = intval($_GET['isHighDensity']);
        }

        $isExtrema = false;
        if(isset($_GET['isExtrema']))
        {
            $this->SendDebug("Web Graph", "isExtrema: ".$_GET['isExtrema'],0);
            $isExtrema = intval($_GET['isExtrema']);
        }

        $isDynamic = false;
        if(isset($_GET['isDynamic']))
        {
            $this->SendDebug("Web Graph", "isDynamic: ".$_GET['isDynamic'],0);
            $isDynamic = intval($_GET['isDynamic']);
        }

        $isContinuous = false;
        if(isset($_GET['isContinuous']))
        {
            $this->SendDebug("Web Graph", "isContinuous: ".$_GET['isContinuous'],0);
            $isContinuous = intval($_GET['isContinuous']);
        }

        $width = 800;
        if(isset($_GET['width']) && intval($_GET['width']) > 0)
        {
            $this->SendDebug("Web Graph", "width: ".$_GET['width'],0);
            $width = intval($_GET['width']);
        }

        $height = 600;
        if(isset($_GET['height']) && intval($_GET['height']) > 0)
        {
            $this->SendDebug("Web Graph", "height: ".$_GET['height'],0);
            $height = intval($_GET['height']);
        }

        $showTitle = true;
        if(isset($_GET['showTitle']))
        {
            $this->SendDebug("Web Graph", "showTitle: ".$_GET['showTitle'],0);
            $showTitle = intval($_GET['showTitle']);
        }

        $showLegend = true;
        if(isset($_GET['showLegend']))
        {
            $this->SendDebug("Web Graph", "showLegend: ".$_GET['showLegend'],0);
            $showLegend = intval($_GET['showLegend']);
        }

        $backgroundColor = "black";
        if(isset($_GET['backgroundColor']))
        {
            $this->SendDebug("Web Graph", "backgroundColor: ".$_GET['backgroundColor'],0);
            $backgroundColor = $_GET['backgroundColor'];
        }


        //Calculate proper startTime
        if($isContinuous) {
            switch ($timeSpan) {
                case 0: //Hour
                    $startTime = mktime(date("H", $startTime) - 1, floor(date("i", $startTime) / 5) * 5 + 5, 0, date("m", $startTime), date("d", $startTime), date("Y", $startTime));
                    break;
                case 1: //Day
                    $startTime = mktime(date("H", $startTime) + 1, 0, 0, date("m", $startTime), date("d", $startTime) - 1, date("Y", $startTime));
                    break;
                case 2: //Week
                    $startTime = mktime(0, 0, 0, date("m", $startTime), date("d", $startTime) - 7 + 1, date("Y", $startTime));
                    break;
                case 3: //Month
                    $startTime = mktime(0, 0, 0, date("m", $startTime), date("d", $startTime) - date("t", $startTime) + 1, date("Y", $startTime));
                    break;
                case 4: //Year
                    $startTime = mktime(0, 0, 0, date("m", $startTime) + 1, 1, date("Y", $startTime) - 1);
                    break;
                case 5: //Decade
                    $startTime = mktime(0, 0, 0, 1, 1, date("Y", $startTime) - 9);
                    break;
                default:
                    echo "Invalid timespan";

                    return;
            }
        } else {
            switch ($timeSpan) {
                case 0: //Hour
                    $startTime = mktime(date("H", $startTime), 0, 0, date("m", $startTime), date("d", $startTime), date("Y", $startTime));
                    break;
                case 1: //Day
                    $startTime = mktime(0, 0, 0, date("m", $startTime), date("d", $startTime), date("Y", $startTime));
                    break;
                case 2: //Week
                    $startTime = mktime(0, 0, 0, date("m", $startTime), date("d", $startTime) - date("N", $startTime) + 1, date("Y", $startTime));
                    break;
                case 3: //Month
                    $startTime = mktime(0, 0, 0, date("m", $startTime), 1, date("Y", $startTime));
                    break;
                case 4: //Year
                    $startTime = mktime(0, 0, 0, 1, 1, date("Y", $startTime));
                    break;
                case 5: //Decade
                    $startTime = mktime(0, 0, 0, 1, 1, floor(date("Y", $startTime) / 10) * 10);
                    break;
                default:
                    echo "Invalid timespan";

                    return;
            }
        }

        $css = file_get_contents(__DIR__ . "/style.css");

        //Add CSS for multi charts
        if (IPS_MediaExists($id)) {
            $css .= PHP_EOL . PHP_EOL;
            $css .= $this->BuildCSSForMultiChart($id);
        }

        $legend = "";
        if($showLegend) {
            if (IPS_MediaExists($id)) {
                $legend = $this->BuildLegendForMultiChart($id) . "<br/>";
            } else {
                $legend = "Name: " . IPS_GetName($id) . "<br/>";
            }
        }

        $acID = IPS_GetInstanceListByModuleID("{43192F0B-135B-4CE7-A0A7-1475603F3060}")[0];
        $chart = AC_RenderChart($acID, $id, $startTime, $timeSpan, $isHighDensity, $isExtrema, $isDynamic, $width, $height);

        //Translate strings
        $chart = $this->TranslateChart($chart);

        //Bail out on error
        if($chart === false) {
            return;
        }

        $title = "";
        if($showTitle) {
            $title = $this->Translate("Start time") . ": " . date("d.m.Y H:i", $startTime) . "<br/>";
        }

        echo '<html>
<head><style>body { background: '.$this->GetBackgroundColor($backgroundColor).'; color: white; font-family: Verdana }'.$css.'</style></head>
<body>
<div class="ipsChart">'.
$title.
$legend.
$chart.'
</div>
</body>';

    }

}

?>