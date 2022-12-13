(function($) {
    $(window).load(function() {
        
        $(".chosen-select").chosen();
        
        /* Shipping Zone Section */
        $('body')
                .on('click', 'a.shipping-zone-delete', function() {
                    var answer = confirm($(this).data('message'));
                    if (answer) {
                        return true;
                    } else {
                        return false;
                    }
                })

                .on('change', 'input[name=zone_type]', function() {
                    if ($(this).is(':checked')) {
                        var value = $(this).val();

                        $('#add-zone input[type="radio"]').each(function() {
                            var tmp_nm = $(this).val();
                            $('.zone_type_' + tmp_nm).removeClass("active_zone");
                        });
                        $('.zone_type_' + value).addClass("active_zone");
                    } else {

                    }
                })

                .on('click', '.select_us_states', function() {
                    $(this).closest('div').find('option[value="US:AK"], option[value="US:AL"], option[value="US:AZ"], option[value="US:AR"], option[value="US:CA"], option[value="US:CO"], option[value="US:CT"], option[value="US:DE"], option[value="US:DC"], option[value="US:FL"], option[value="US:GA"], option[value="US:HI"], option[value="US:ID"], option[value="US:IL"], option[value="US:IN"], option[value="US:IA"], option[value="US:KS"], option[value="US:KY"], option[value="US:LA"], option[value="US:ME"], option[value="US:MD"], option[value="US:MA"], option[value="US:MI"], option[value="US:MN"], option[value="US:MS"], option[value="US:MO"], option[value="US:MT"], option[value="US:NE"], option[value="US:NV"], option[value="US:NH"], option[value="US:NJ"], option[value="US:NM"], option[value="US:NY"], option[value="US:NC"], option[value="US:ND"], option[value="US:OH"], option[value="US:OK"], option[value="US:OR"], option[value="US:PA"], option[value="US:RI"], option[value="US:SC"], option[value="US:SD"], option[value="US:TN"], option[value="US:TX"], option[value="US:UT"], option[value="US:VT"], option[value="US:VA"], option[value="US:WA"], option[value="US:WV"], option[value="US:WI"], option[value="US:WY"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_europe', function() {
                    $(this).closest('div').find('option[value="AL"], option[value="AD"], option[value="AM"], option[value="AT"], option[value="BY"], option[value="BE"], option[value="BA"], option[value="BG"], option[value="CH"], option[value="CY"], option[value="CZ"], option[value="DE"], option[value="DK"], option[value="EE"], option[value="ES"], option[value="FO"], option[value="FI"], option[value="FR"], option[value="GB"], option[value="GE"], option[value="GI"], option[value="GR"], option[value="HU"], option[value="HR"], option[value="IE"], option[value="IS"], option[value="IT"], option[value="LT"], option[value="LU"], option[value="LV"], option[value="MC"], option[value="MK"], option[value="MT"], option[value="NO"], option[value="NL"], option[value="PO"], option[value="PT"], option[value="RO"], option[value="RU"], option[value="SE"], option[value="SI"], option[value="SK"], option[value="SM"], option[value="TR"], option[value="UA"], option[value="VA"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_asia', function() {
                    $(this).closest('div').find('option[value="AE"], option[value="AF"], option[value="AM"], option[value="AZ"], option[value="BD"], option[value="BH"], option[value="BN"], option[value="BT"], option[value="CC"], option[value="CN"], option[value="CX"], option[value="CY"], option[value="GE"], option[value="HK"], option[value="ID"], option[value="IL"], option[value="IN"], option[value="IO"], option[value="IQ"], option[value="IR"], option[value="JO"], option[value="JP"], option[value="KG"], option[value="KH"], option[value="KP"], option[value="KR"], option[value="KW"], option[value="KZ"], option[value="LA"], option[value="LB"], option[value="LK"], option[value="MM"], option[value="MN"], option[value="MO"], option[value="MV"], option[value="MY"], option[value="NP"], option[value="OM"], option[value="PH"], option[value="PK"], option[value="PS"], option[value="QA"], option[value="SA"], option[value="SG"], option[value="SY"], option[value="TH"], option[value="TJ"], option[value="TL"], option[value="TM"], option[value="TW"], option[value="UZ"], option[value="VN"], option[value="YE"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_africa', function() {
                    $(this).closest('div').find(' option[value="AO"], option[value="BF"], option[value="BI"], option[value="BJ"], option[value="BW"], option[value="CD"], option[value="CF"], option[value="CG"], option[value="CI"], option[value="CM"], option[value="CV"], option[value="DJ"], option[value="DZ"], option[value="EG"], option[value="EH"], option[value="ER"], option[value="ET"], option[value="GA"], option[value="GH"], option[value="GM"], option[value="GN"], option[value="GQ"], option[value="GW"], option[value="KE"], option[value="KM"], option[value="LR"], option[value="LS"], option[value="LY"], option[value="MA"], option[value="MG"], option[value="ML"], option[value="MR"], option[value="MU"], option[value="MW"], option[value="MZ"], option[value="NA"], option[value="NE"], option[value="NG"], option[value="RE"], option[value="RW"], option[value="SC"], option[value="SD"], option[value="SS"], option[value="SH"], option[value="SL"], option[value="SN"], option[value="SO"], option[value="ST"], option[value="SZ"], option[value="TD"], option[value="TG"], option[value="TN"], option[value="TZ"], option[value="UG"], option[value="YT"], option[value="ZA"], option[value="ZM"], option[value="ZW"]')
                            .attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_antarctica', function() {
                    $(this).closest('div').find('option[value="AQ"], option[value="BV"], option[value="GS"], option[value="HM"], option[value="TF"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_northamerica', function() {
                    $(this).closest('div').find('option[value="AG"], option[value="AI"], option[value="AN"], option[value="AW"], option[value="BB"], option[value="BL"], option[value="BM"], option[value="BS"], option[value="BZ"], option[value="CA"], option[value="CR"], option[value="CU"], option[value="DM"], option[value="DO"], option[value="GD"], option[value="GL"], option[value="GP"], option[value="GT"], option[value="HN"], option[value="HT"], option[value="JM"], option[value="KN"], option[value="KY"], option[value="LC"], option[value="MF"], option[value="MQ"], option[value="MS"], option[value="MX"], option[value="NI"], option[value="PA"], option[value="PM"], option[value="PR"], option[value="SV"], option[value="TC"], option[value="TT"], option[value="US"], option[value="VC"], option[value="VG"], option[value="VI"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_oceania', function() {
                    $(this).closest('div').find(' option[value="AS"], option[value="AU"], option[value="CK"], option[value="FJ"], option[value="FM"], option[value="GU"], option[value="KI"], option[value="MH"], option[value="MP"], option[value="NC"], option[value="NF"], option[value="NR"], option[value="NU"], option[value="NZ"], option[value="PF"], option[value="PG"], option[value="PN"], option[value="PW"], option[value="SB"], option[value="TK"], option[value="TO"], option[value="TV"], option[value="UM"], option[value="VU"], option[value="WF"], option[value="WS"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_southamerica', function() {
                    $(this).closest('div').find(' option[value="AR"], option[value="BO"], option[value="BR"], option[value="CL"], option[value="CO"], option[value="EC"], option[value="FK"], option[value="GF"], option[value="GY"], option[value="PE"], option[value="PY"], option[value="SR"], option[value="UY"], option[value="VE"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_africa_states', function() {
                    $(this).closest('div').find('option[value="ZA:EC"], option[value="ZA:FS"], option[value="ZA:GP"], option[value="ZA:KZN"], option[value="ZA:LP"], option[value="ZA:MP"], option[value="ZA:NC"], option[value="ZA:NW"], option[value="ZA:WC"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_asia_states', function() {
                    $(this).closest('div').find('option[value="BD"],option[value="BD:BAG"], option[value="BD:BAN"], option[value="BD:BAR"], option[value="BD:BARI"], option[value="BD:BHO"], option[value="BD:BOG"], option[value="BD:BRA"], option[value="BD:CHA"], option[value="BD:CHI"], option[value="BD:CHU"], option[value="BD:COM"], option[value="BD:COX"], option[value="BD:DHA"], option[value="BD:DIN"], option[value="BD:FAR"], option[value="BD:FEN"], option[value="BD:GAI"], option[value="BD:GAZI"], option[value="BD:GOP"], option[value="BD:HAB"], option[value="BD:JAM"], option[value="BD:JES"], option[value="BD:JHA"], option[value="BD:JHE"], option[value="BD:JOY"], option[value="BD:KHA"], option[value="BD:KHU"], option[value="BD:KIS"], option[value="BD:KUR"], option[value="BD:KUS"], option[value="BD:LAK"], option[value="BD:LAL"], option[value="BD:MAD"], option[value="BD:MAG"], option[value="BD:MAN"], option[value="BD:MEH"], option[value="BD:MOU"], option[value="BD:MUN"], option[value="BD:MYM"], option[value="BD:NAO"], option[value="BD:NAR"], option[value="BD:NARG"], option[value="BD:NARD"], option[value="BD:NAT"], option[value="BD:NAW"], option[value="BD:NET"], option[value="BD:NIL"], option[value="BD:NOA"], option[value="BD:PAB"], option[value="BD:PAN"], option[value="BD:PAT"], option[value="BD:PIR"], option[value="BD:RAJB"], option[value="BD:RAJ"], option[value="BD:RAN"], option[value="BD:RANP"], option[value="BD:SAT"], option[value="BD:SHA"], option[value="BD:SHE"], option[value="BD:SIR"], option[value="BD:SUN"], option[value="BD:SYL"], option[value="BD:TAN"], option[value="BD:THA"],option[value="CN:CN1"], option[value="CN:CN2"],option[value="CN"], option[value="CN:CN3"], option[value="CN:CN4"], option[value="CN:CN5"], option[value="CN:CN6"], option[value="CN:CN7"], option[value="CN:CN8"], option[value="CN:CN9"], option[value="CN:CN10"], option[value="CN:CN11"], option[value="CN:CN12"], option[value="CN:CN13"], option[value="CN:CN14"], option[value="CN:CN15"], option[value="CN:CN16"], option[value="CN:CN17"], option[value="CN:CN18"], option[value="CN:CN19"], option[value="CN:CN20"], option[value="CN:CN21"], option[value="CN:CN22"], option[value="CN:CN23"], option[value="CN:CN24"], option[value="CN:CN25"], option[value="CN:CN26"], option[value="CN:CN27"], option[value="CN:CN28"], option[value="CN:CN29"], option[value="CN:CN30"], option[value="CN:CN31"], option[value="CN:CN32"],option[value="HK:HONG KONG"], option[value="HK:KOWLOON"], option[value="HK:NEW TERRITORIES"], option[value="HK:KOWLOON"], option[value="HK:NEW TERRITORIES"],option[value="HK"], option[value="ID"], option[value="ID:AC"], option[value="ID:SU"], option[value="ID:SB"], option[value="ID:RI"], option[value="ID:KR"], option[value="ID:JA"], option[value="ID:SS"], option[value="ID:BB"], option[value="ID:BE"], option[value="ID:LA"], option[value="ID:JK"], option[value="ID:JB"], option[value="ID:BT"], option[value="ID:JT"], option[value="ID:JI"], option[value="ID:YO"], option[value="ID:BA"], option[value="ID:NB"], option[value="ID:NT"], option[value="ID:KB"], option[value="ID:KT"], option[value="ID:KI"], option[value="ID:KS"], option[value="ID:KU"], option[value="ID:SA"], option[value="ID:ST"], option[value="ID:SG"], option[value="ID:SR"], option[value="ID:SN"], option[value="ID:GO"], option[value="ID:MA"], option[value="ID:MU"], option[value="ID:PA"], option[value="ID:PB"],option[value="IN"], option[value="IN:AP"], option[value="IN:AR"], option[value="IN:AS"], option[value="IN:BR"], option[value="IN:CT"], option[value="IN:GA"], option[value="IN:GJ"], option[value="IN:HR"], option[value="IN:HP"], option[value="IN:JK"], option[value="IN:JH"], option[value="IN:KA"], option[value="IN:KL"], option[value="IN:MP"], option[value="IN:MH"], option[value="IN:MN"], option[value="IN:ML"], option[value="IN:MZ"], option[value="IN:NL"], option[value="IN:OR"], option[value="IN:PB"], option[value="IN:RJ"], option[value="IN:SK"], option[value="IN:TN"], option[value="IN:TS"], option[value="IN:TR"], option[value="IN:UK"], option[value="IN:UP"], option[value="IN:WB"], option[value="IN:AN"], option[value="IN:CH"], option[value="IN:DN"], option[value="IN:DD"], option[value="IN:DL"], option[value="IN:LD"], option[value="IN:PY"],option[value="IR"], option[value="IR:KHZ"], option[value="IR:THR"], option[value="IR:ILM"], option[value="IR:BHR"], option[value="IR:ADL"], option[value="IR:ESF"], option[value="IR:YZD"], option[value="IR:KRH"], option[value="IR:KRN"], option[value="IR:HDN"], option[value="IR:GZN"], option[value="IR:ZJN"], option[value="IR:LRS"], option[value="IR:ABZ"], option[value="IR:EAZ"], option[value="IR:WAZ"], option[value="IR:CHB"], option[value="IR:SKH"], option[value="IR:RKH"], option[value="IR:NKH"], option[value="IR:SMN"], option[value="IR:FRS"], option[value="IR:QHM"], option[value="IR:KRD"], option[value="IR:KBD"], option[value="IR:GLS"], option[value="IR:GIL"], option[value="IR:MZN"], option[value="IR:MKZ"], option[value="IR:HRZ"], option[value="IR:SBN"],option[value="JP"], option[value="JP:JP01"], option[value="JP:JP02"], option[value="JP:JP03"], option[value="JP:JP04"], option[value="JP:JP05"], option[value="JP:JP06"], option[value="JP:JP07"], option[value="JP:JP08"], option[value="JP:JP09"], option[value="JP:JP10"], option[value="JP:JP11"], option[value="JP:JP12"], option[value="JP:JP13"], option[value="JP:JP14"], option[value="JP:JP15"], option[value="JP:JP16"], option[value="JP:JP17"], option[value="JP:JP18"], option[value="JP:JP19"], option[value="JP:JP20"], option[value="JP:JP21"], option[value="JP:JP22"], option[value="JP:JP23"], option[value="JP:JP24"], option[value="JP:JP25"], option[value="JP:JP26"], option[value="JP:JP27"], option[value="JP:JP28"], option[value="JP:JP29"], option[value="JP:JP30"], option[value="JP:JP31"], option[value="JP:JP32"], option[value="JP:JP33"], option[value="JP:JP34"], option[value="JP:JP35"], option[value="JP:JP36"], option[value="JP:JP37"], option[value="JP:JP38"], option[value="JP:JP39"], option[value="JP:JP40"], option[value="JP:JP41"], option[value="JP:JP42"], option[value="JP:JP43"], option[value="JP:JP44"], option[value="JP:JP45"], option[value="JP:JP46"], option[value="JP:JP47"],option[value="MY"], option[value="MY:JHR"], option[value="MY:KDH"], option[value="MY:KTN"], option[value="MY:LBN"], option[value="MY:MLK"], option[value="MY:NSN"], option[value="MY:PHG"], option[value="MY:PNG"], option[value="MY:PRK"], option[value="MY:PLS"], option[value="MY:SBH"], option[value="MY:SWK"], option[value="MY:SGR"], option[value="MY:TRG"], option[value="MY:PJY"], option[value="MY:KUL"],option[value="NP"], option[value="NP:BAG"], option[value="NP:BHE"], option[value="NP:DHA"], option[value="NP:GAN"], option[value="NP:JAN"], option[value="NP:KAR"], option[value="NP:KOS"], option[value="NP:LUM"], option[value="NP:MAH"], option[value="NP:MEC"], option[value="NP:NAR"], option[value="NP:RAP"], option[value="NP:SAG"], option[value="NP:SET"],option[value="PH"], option[value="PH:ABR"], option[value="PH:AGN"], option[value="PH:AGS"], option[value="PH:AKL"], option[value="PH:ALB"], option[value="PH:ANT"], option[value="PH:APA"], option[value="PH:AUR"], option[value="PH:BAS"], option[value="PH:BAN"], option[value="PH:BTN"], option[value="PH:BTG"], option[value="PH:BEN"], option[value="PH:BIL"], option[value="PH:BOH"], option[value="PH:BUK"], option[value="PH:BUL"], option[value="PH:CAG"], option[value="PH:CAN"], option[value="PH:CAS"], option[value="PH:CAM"], option[value="PH:CAP"], option[value="PH:CAT"], option[value="PH:CAV"], option[value="PH:CEB"], option[value="PH:COM"], option[value="PH:NCO"], option[value="PH:DAV"], option[value="PH:DAS"], option[value="PH:DAC"], option[value="PH:DAO"], option[value="PH:DIN"], option[value="PH:EAS"], option[value="PH:GUI"], option[value="PH:IFU"], option[value="PH:ILN"], option[value="PH:ILS"], option[value="PH:ILI"], option[value="PH:ISA"], option[value="PH:KAL"], option[value="PH:LUN"], option[value="PH:LAG"], option[value="PH:LAN"], option[value="PH:LAS"], option[value="PH:LEY"], option[value="PH:MAG"], option[value="PH:MAD"], option[value="PH:MAS"], option[value="PH:MSC"], option[value="PH:MSR"], option[value="PH:MOU"], option[value="PH:NEC"], option[value="PH:NER"], option[value="PH:NSA"], option[value="PH:NUE"], option[value="PH:NUV"], option[value="PH:MDC"], option[value="PH:MDR"], option[value="PH:PLW"], option[value="PH:PAM"], option[value="PH:PAN"], option[value="PH:QUE"], option[value="PH:QUI"], option[value="PH:RIZ"], option[value="PH:ROM"], option[value="PH:WSA"], option[value="PH:SAR"], option[value="PH:SIQ"], option[value="PH:SOR"], option[value="PH:SCO"], option[value="PH:SLE"], option[value="PH:SUK"], option[value="PH:SLU"], option[value="PH:SUN"], option[value="PH:SUR"], option[value="PH:TAR"], option[value="PH:TAW"], option[value="PH:ZMB"], option[value="PH:ZAN"], option[value="PH:ZAS"], option[value="PH:ZSI"], option[value="PH:00"],option[value="TH"], option[value="TH:TH-37"], option[value="TH:TH-15"], option[value="TH:TH-14"], option[value="TH:TH-10"], option[value="TH:TH-38"], option[value="TH:TH-31"], option[value="TH:TH-24"], option[value="TH:TH-18"], option[value="TH:TH-36"], option[value="TH:TH-22"], option[value="TH:TH-50"], option[value="TH:TH-57"], option[value="TH:TH-20"], option[value="TH:TH-86"], option[value="TH:TH-46"], option[value="TH:TH-62"], option[value="TH:TH-71"], option[value="TH:TH-40"], option[value="TH:TH-81"], option[value="TH:TH-52"], option[value="TH:TH-51"], option[value="TH:TH-42"], option[value="TH:TH-16"], option[value="TH:TH-58"], option[value="TH:TH-44"], option[value="TH:TH-49"], option[value="TH:TH-26"], option[value="TH:TH-73"], option[value="TH:TH-48"], option[value="TH:TH-30"], option[value="TH:TH-60"], option[value="TH:TH-80"], option[value="TH:TH-55"], option[value="TH:TH-96"], option[value="TH:TH-39"], option[value="TH:TH-43"], option[value="TH:TH-12"], option[value="TH:TH-13"], option[value="TH:TH-94"], option[value="TH:TH-82"], option[value="TH:TH-93"], option[value="TH:TH-56"], option[value="TH:TH-67"], option[value="TH:TH-76"], option[value="TH:TH-66"], option[value="TH:TH-65"], option[value="TH:TH-54"], option[value="TH:TH-83"], option[value="TH:TH-25"], option[value="TH:TH-77"], option[value="TH:TH-85"], option[value="TH:TH-70"], option[value="TH:TH-21"], option[value="TH:TH-45"], option[value="TH:TH-27"], option[value="TH:TH-47"], option[value="TH:TH-11"], option[value="TH:TH-74"], option[value="TH:TH-75"], option[value="TH:TH-19"], option[value="TH:TH-91"], option[value="TH:TH-17"], option[value="TH:TH-33"], option[value="TH:TH-90"], option[value="TH:TH-64"], option[value="TH:TH-72"], option[value="TH:TH-84"], option[value="TH:TH-32"], option[value="TH:TH-63"], option[value="TH:TH-92"], option[value="TH:TH-23"], option[value="TH:TH-34"], option[value="TH:TH-41"], option[value="TH:TH-61"], option[value="TH:TH-53"], option[value="TH:TH-95"], option[value="TH:TH-35"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_oceania_states', function() {
                    $(this).closest('div').find('option[value="AU"], option[value="AU:ACT"], option[value="AU:NSW"], option[value="AU:NT"], option[value="AU:QLD"], option[value="AU:SA"], option[value="AU:TAS"], option[value="AU:VIC"], option[value="AU:WA"],option[value="NZ"], option[value="NZ:NL"], option[value="NZ:AK"], option[value="NZ:WA"], option[value="NZ:BP"], option[value="NZ:TK"], option[value="NZ:GI"], option[value="NZ:HB"], option[value="NZ:MW"], option[value="NZ:WE"], option[value="NZ:NS"], option[value="NZ:MB"], option[value="NZ:TM"], option[value="NZ:WC"], option[value="NZ:CT"], option[value="NZ:OT"], option[value="NZ:SL"]').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_none', function() {
                    $(this).closest('div').find('select option').removeAttr("selected");
                    $(this).closest('div').find('select').trigger('chosen:updated').change();
                    return false;
                })

                .on('click', '.select_all', function() {
                    $(this).closest('div').find('select option').attr("selected", "selected");
                    $(this).closest('div').find('select').trigger('chosen:updated');
                    return false;
                });

        $('.zone_type_options').hide();
        $('input[name=zone_type]').change();

        // Select availability
        $('select.availability').change(function() {
            if ($(this).val() === 'all') {
                $(this).closest('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').next('tr').next('tr').hide();
            } else if ($(this).val() === 'specific') {
                $(this).closest('tr').next('tr').show();
                $(this).closest('tr').next('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').next('tr').next('tr').hide();
            } else if ($(this).val() === 'Countrybase') {
                $(this).closest('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').show();
                $(this).closest('tr').next('tr').next('tr').next('tr').show();
                $(this).closest('tr').next('tr').next('tr').next('tr').next('tr').show();
            } else {
                $(this).closest('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').next('tr').hide();
                $(this).closest('tr').next('tr').next('tr').next('tr').next('tr').hide();
            }
        }).change();
        /* Shipping Zone Section */
        
        $("#afrsm_dialog").dialog({
            modal: true, title: 'Subscribe To Our Newsletter', zIndex: 10000, autoOpen: true,
            width: '500', resizable: false,
            position: {my: "center", at: "center", of: window},
            dialogClass: 'dialogButtons',
            buttons: [
                {
                    id: "Delete",
                    text: "Subscribe Me",
                    click: function() {
                        var email_id = jQuery('#txt_user_sub_afrsm').val();
                        var data = {
                            'action': 'add_plugin_user_afrsm_pro',
                            'email_id': email_id
                        };
                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        jQuery.post(ajaxurl, data, function(response) {
                            jQuery('#afrsm_dialog').html('<h2>You have been successfully subscribed');
                            jQuery(".ui-dialog-buttonpane").remove();
                        });
                    }
                },
                {
                    id: "No",
                    text: "No, Remind Me Later",
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }
            ]
        });
        jQuery("div.dialogButtons .ui-dialog-buttonset button").removeClass('ui-state-default');
        jQuery("div.dialogButtons .ui-dialog-buttonset button").addClass("button-primary woocommerce-save-button");
        jQuery(".multiselect2").chosen();
        $("#sm_start_date").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: '0',
            onSelect: function(selected) {
                var dt = $(this).datepicker('getDate');
                dt.setDate(dt.getDate() + 1);
                $("#sm_end_date").datepicker("option", "minDate", dt);
            }
        });
        $("#sm_end_date").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: '0',
            onSelect: function(selected) {
                var dt = $(this).datepicker('getDate');
                dt.setDate(dt.getDate() - 1);
                $("#sm_start_date").datepicker("option", "maxDate", dt);
            }
        });
        var ele = $('#total_row').val();
        if (ele > 2) {
            var count = ele;
        } else {
            var count = 2;
        }
        $('body').on('click', '#fee-add-field', function() {
            var tds = '<tr id=row_' + count + '>';
            tds += '<td><select rel-id=' + count + ' id=product_fees_conditions_condition_' + count + ' name="fees[product_fees_conditions_condition][]" class="product_fees_conditions_condition"><optgroup label="Location Specific"><option value="country">Country</option><option value="state">State</option><option value="postcode">Postcode</option><option value="zone">Zone</option></optgroup><optgroup label="Product Specific"><option value="product">Cart contains product</option> <option value="variableproduct">Cart contains variable product</option><option value="category">Cart contains category\'s product</option><option value="tag">Cart contains tag\'s product</option><option value="sku">Cart contains SKU\'s product</option></optgroup><optgroup label="User Specific"><option value="user">User</option><option value="user_role">User Role</option></optgroup><optgroup label="Cart Specific"><option value="cart_total">Cart Subtotal (Before Discount)</option><option value="cart_totalafter">Cart Subtotal (After Discount)</option><option value="quantity">Quantity</option><option value="weight">Weight</option><option value="coupon">Coupon</option><option value="shipping_class">Shipping Class</option></optgroup></select></td>';
            tds += '<td><select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is product_fees_conditions_is_' + count + '"><option value="is_equal_to">Equal to ( = )</option><option value="not_in">Not Equal to ( != )</option></select></td>';
            tds += '<td id=column_' + count + '><select name="fees[product_fees_conditions_values][value_' + count + '][]" class="product_fees_conditions_values product_fees_conditions_values_' + count + ' multiselect2" multiple="multiple"></select><input type="hidden" name="condition_key[value_' + count + '][]" value=""></td>';
            tds += '<td><a id="fee-delete-field" rel-id="' + count + '" title="Delete" class="delete-row" href="javascript:;"><i class="fa fa-trash"></i></a></td>';
            tds += '</tr>';
            $('#tbl-shipping-method').append(tds);
            jQuery(".product_fees_conditions_values_" + count).append(jQuery(".default-country-box select").html());
            jQuery(".product_fees_conditions_values_" + count).trigger("chosen:updated");
            jQuery(".multiselect2").chosen();
            count++;
        });
        $('body').on('click', '#fee-delete-field', function() {
            var deleId = $(this).attr('rel-id');
            $("#row_" + deleId).remove();
        });
        $('body').on('change', '.product_fees_conditions_condition', function() {
            var condition = $(this).val();
            var count = $(this).attr('rel-id');
            $('#column_' + count).html('<img src="' + coditional_vars.plugin_url + 'images/ajax-loader.gif">');
            var data = {
                'action': 'afrsm_pro_product_fees_conditions_values_ajax',
                'condition': condition,
                'count': count
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                if (condition == 'cart_total' || condition == 'cart_totalafter' || condition == 'quantity' || condition == 'weight') {
                    jQuery('.product_fees_conditions_is_' + count).html('');
                    jQuery('.product_fees_conditions_is_' + count).append(jQuery(".text-condtion-is select.text-condition").html());
                    jQuery('.product_fees_conditions_is_' + count).trigger("chosen:updated");
                } else {
                    jQuery('.product_fees_conditions_is_' + count).html('');
                    jQuery('.product_fees_conditions_is_' + count).append(jQuery(".text-condtion-is select.select-condition").html());
                    jQuery('.product_fees_conditions_is_' + count).trigger("chosen:updated");
                }
                $('#column_' + count).html('');
                $('#column_' + count).append(response);
                $('#column_' + count).append('<input type="hidden" name="condition_key[value_' + count + '][]" value="">');
                jQuery(".multiselect2").chosen();
                if (condition == 'product') {
                    $('#product_filter_chosen input').val('Please enter 3 or more characters');
                }

            });
        });
        $('body').on('keyup', '#product_filter_chosen input', function() {
            var countId = $(this).closest("td").attr('id');
            $('#product_filter_chosen ul li.no-results').html('Please enter 3 or more characters');
            var value = $(this).val();
            var valueLenght = value.replace(/\s+/g, '');
            var valueCount = valueLenght.length;
            var remainCount = 3 - valueCount;
            if (valueCount >= 3) {
                $('#product_filter_chosen ul li.no-results').html('<img src="' + coditional_vars.plugin_url + 'images/ajax-loader.gif">');
                var data = {
                    'action': 'afrsm_pro_product_fees_conditions_values_product_ajax',
                    'value': value
                };
                jQuery.post(ajaxurl, data, function(response) {

                    if (response.length != 0) {
                        $('#' + countId + ' #product-filter').append(response);
                    } else {
                        $('#product-filter option').not(':selected').remove();
                    }
                    $('#' + countId + ' #product-filter option').each(function() {
                        $(this).siblings("[value='" + this.value + "']").remove();
                    });
                    jQuery('#' + countId + ' #product-filter').trigger("chosen:updated");
                    $('#product_filter_chosen .search-field input').val(value);
                    $('#' + countId + ' #product-filter').chosen().change(function() {
                        var productVal = $('#' + countId + ' #product-filter').chosen().val();
                        jQuery('#' + countId + ' #product-filter option').each(function() {
                            $(this).siblings("[value='" + this.value + "']").remove();
                            if (jQuery.inArray(this.value, productVal) == -1) {
                                jQuery(this).remove();
                            }
                        });
                        jQuery('#' + countId + ' #product-filter').trigger("chosen:updated");
                    });
                    $('#product_filter_chosen ul li.no-results').html('');
                });
            } else {
                if (remainCount > 0) {
                    $('#product_filter_chosen ul li.no-results').html('Please enter ' + remainCount + ' or more characters');
                }
            }
        });
        $('body').on('keyup', '#var_product_filter_chosen input', function () {
            countId = $(this).closest("td").attr('id');
            $('#var_product_filter_chosen ul li.no-results').html('Please enter 3 or more characters');
            var value = $(this).val();
            var valueLenght = value.replace(/\s+/g, '');
            var valueCount = valueLenght.length;
            var remainCount = 3 - valueCount;
            var selproductvalue = $('#' + countId + ' #var-product-filter').chosen().val();
            if (valueCount >= 3) {
                $('#var_product_filter_chosen ul li.no-results').html('<img src="' + coditional_vars.plugin_url + 'images/ajax-loader.gif">');
                var data = {
                    'action': 'afrsm_pro_product_fees_conditions_varible_values_product_ajax',
                    'value': value,
                };
                jQuery.post(ajaxurl, data, function (response) {
                    if (response.length != 0) {
                        $('#' + countId + ' #var-product-filter').append(response);
                    } else {
                        $('#var-product-filter option').not(':selected').remove();
                    }
                    $('#' + countId + ' #var-product-filter option').each(function () {
                        $(this).siblings("[value='" + this.value + "']").remove();
                    });
                    jQuery('#' + countId + ' #var-product-filter').trigger("chosen:updated");
                    $('#var_product_filter_chosen .search-field input').val(value);
                    $('#' + countId + ' #var-product-filter').chosen().change(function () {
                        var productVal = $('#' + countId + ' #var-product-filter').chosen().val();
                        jQuery('#' + countId + ' #var-product-filter option').each(function () {
                            $(this).siblings("[value='" + this.value + "']").remove();
                            if (jQuery.inArray(this.value, productVal) == -1) {
                                jQuery(this).remove();
                            }
                        });
                        jQuery('#' + countId + ' #var-product-filter').trigger("chosen:updated");
                    });
                    $('#var_product_filter_chosen ul li.no-results').html('');
                });
            } else {
                if (remainCount > 0) {
                    $('#var_product_filter_chosen ul li.no-results').html('Please enter ' + remainCount + ' or more characters');
                }
            }
        });
        $(".condition-check-all").click(function() {
            $('input.multiple_delete_fee:checkbox').not(this).prop('checked', this.checked);
        });
        $('#delete-shipping-method').click(function() {
            if ($('.multiple_delete_fee:checkbox:checked').length == 0) {
                alert('Please select at least one shipping method');
                return false;
            }
            if (confirm('Are You Sure You Want to Delete?')) {
                var allVals = [];
                $(".multiple_delete_fee:checked").each(function() {
                    allVals.push($(this).val());
                });
                var data = {
                    'action': 'afrsm_pro_wc_multiple_delete_shipping_method',
                    'allVals': allVals
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    if (response == 1) {
                        alert('Delete Successfully');
                        $(".multiple_delete_fee").prop("checked", false);
                        location.reload();
                    }
                });
            }
        });
        /* description toggle */
        $('span.advanced_flat_rate_shipping_for_woocommerce_tab_description').click(function(event) {
            event.preventDefault();
            var data = $(this);
            $(this).next('p.description').toggle();
        });
    });
    jQuery(document).ready(function($) {
        $(".tablesorter").tablesorter({
            headers: {
                0: {
                    sorter: false
                },
                4: {
                    sorter: false
                }
            }
        });
        var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        };
        //Make diagnosis table sortable
        $("table#shipping-methods-listing tbody").sortable({
            helper: fixHelperModified
        });
        $("table#shipping-methods-listing tbody").disableSelection();
        
        $(document).on( 'click', '.shipping-methods-order', function() {
            var smOrderArray = [];
            
            $('table#shipping-methods-listing tbody tr').each(function() {
                smOrderArray.push(this.id);
            });
            
            var data = {
                'action': 'sm_sort_order',
                'smOrderArray': smOrderArray
            };
            
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                alert('Shipping method order saved successfully');
            });
            
        });
        
        //Save Master Settings
        $(document).on('click', '#save_master_settings', function() {
            var what_to_do = $('#what_to_do_method').val();
            var shipping_display_mode = $('#shipping_display_mode').val();

            var data = {
                'action': 'save_master_settings',
                'what_to_do': what_to_do,
                'shipping_display_mode': shipping_display_mode
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                
                $('<div class="ms-msg">Your settings successfully saved.</div>').insertBefore( ".afrsm-section-left .afrsm-main-table" );
                $("html, body").animate({ scrollTop: 0 }, "slow");
                setTimeout(function(){
                    $('.ms-msg').remove();
                }, 2000);
                
            });

        });

        //Subscribe Newsletter
        $("#dotstore_subscribe_dialog").dialog({
            modal: true, title: 'Subscribe To Our Newsletter', zIndex: 10000, autoOpen: true,
            width: '500', resizable: false,
            position: {my: "center", at: "center", of: window},
            dialogClass: 'dialogButtons',
            buttons: [
                {
                    id: "Delete",
                    text: "YES",
                    click: function() {
                        var email_id = jQuery('#txt_user_sub_afrsm').val();
                        var data = {
                            'action': 'afrsm_pro_subscribe_newsletter',
                            'email_id': email_id
                        };
                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        jQuery.post(ajaxurl, data, function(response) {
                            jQuery('#dotstore_subscribe_dialog').html('<h2>You have successfully subscribed to the newsletter</h2>');
                            jQuery(".ui-dialog-buttonpane").remove();
                        });
                    }
                },
                {
                    id: "No",
                    text: "No, Remind Me Later",
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }
            ]
        });
        jQuery("div.dialogButtons .ui-dialog-buttonset button").removeClass('ui-state-default');
        jQuery("div.dialogButtons .ui-dialog-buttonset button").addClass("button-primary woocommerce-save-button");

        /* Apply per quantity conditions start */
        if ($("#fee_chk_qty_price").is(':checked')) {
            $(".afrsm-section-left .afrsm-main-table .product_cost_right_div .applyperqty-boxtwo").show();
            $(".afrsm-main-table .product_cost_right_div .applyperqty-boxthree").show();
            $("#extra_product_cost").prop('required', true);
        } else {
            $(".afrsm-section-left .afrsm-main-table .product_cost_right_div .applyperqty-boxtwo").hide();
            $(".afrsm-main-table .product_cost_right_div .applyperqty-boxthree").hide();
            $("#extra_product_cost").prop('required', false);
        }
        $(document).on('change', '#fee_chk_qty_price', function () {
            if (this.checked) {
                $(".afrsm-main-table .product_cost_right_div .applyperqty-boxtwo").show();
                $(".afrsm-main-table .product_cost_right_div .applyperqty-boxthree").show();
                $("#extra_product_cost").prop('required', true);
            } else {
                $(".afrsm-main-table .product_cost_right_div .applyperqty-boxtwo").hide();
                $(".afrsm-main-table .product_cost_right_div .applyperqty-boxthree").hide();
                $("#extra_product_cost").prop('required', false);
            }
        });
        /* Apply per quantity conditions end */

    });
})(jQuery);