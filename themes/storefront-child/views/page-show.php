<!-- Bootstrap4 -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
    crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
    crossorigin="anonymous"></script> -->
<!-- END Bootstrap4 -->

<link rel="stylesheet" type="text/css"
      href="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.css"
/>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.js"></script>

<?php

//    $atributezzz = array(1 => '', 2 => '', 3 => '', 4 => '', 5 => '', 6 => '', 7 => '', 8 => '', 9 => '', 10 => 'Lounge', 11 => 'TV Room', 12 => 'Living Room', 13 => 'Kitchen ', 14 => 'Family Room', 15 => 'Dining Room', 16 => 'Utility Room', 17 => 'Bed 1', 18 => 'Bed 2', 19 => 'Bed 3', 20 => 'Bed 4', 21 => 'Bed 5', 22 => 'Guest Room', 23 => 'Spare Room', 24 => 'Bathroom Downstairs', 25 => 'Bathroom Upstairs', 26 => 'Study', 27 => 'Games Room', 28 => 'Hallway', 29 => 'Full Height', 30 => 'Café Style', 31 => 'Tier-on-Tier', 32 => 'Bay Window', 33 => 'Special Shaped', 34 => 'French Door Cut', 35 => 'Tracked', 36 => 'Arched Shape', 37 => '', 38 => '', 39 => '', 40 => '', 41 => '', 42 => '', 43 => '', 44 => '', 45 => '', 46 => '', 47 => '', 48 => '', 49 => '', 50 => '', 51 => '', 52 => 'Flat Louver', 53 => '63.5mm', 54 => '76.2mm', 55 => '88.9mm', 56 => 'inside', 57 => 'outside', 58 => '', 59 => 'F', 60 => 'L70', 61 => 'C', 62 => 'D', 63 => 'K', 64 => 'O', 65 => 'Q', 66 => 'N', 67 => 'M', 68 => '', 69 => '', 70 => 'Yes', 71 => 'No', 72 => 'Sill', 73 => 'lightblock', 74 => 'none', 75 => 'Yes', 76 => 'No', 77 => 'Sill', 78 => 'lightblock', 79 => 'none', 80 => 'Yes', 81 => 'No', 82 => 'Sill', 83 => 'lightblock', 84 => 'none', 85 => 'Yes', 86 => 'No', 87 => 'Sill', 88 => 'lightblock', 89 => 'none', 90 => 'White', 91 => 'Brass', 92 => 'Antique Brass', 93 => 'Stainless Steel', 94 => 'Other', 95 => '', 96 => 'Clearview', 97 => 'Centre Rod', 98 => 'Off Centre Rod', 99 => 'Centre Rod Split', 100 => 'Off Centre Rod Split', 101 => 'LS 601 PURE WHITE', 102 => '', 103 => 'LS 003 SILK WHITE', 104 => 'LS 630 MOST WHITE', 105 => 'LS 637 HOG BRISTLE', 106 => 'LS 609 CHAMPAGNE', 107 => 'LS 105 PEARL', 108 => 'LS 618 ALABASTER', 109 => 'LS 619 CREAMY', 110 => 'LS 632 MISTRA', 111 => 'LS 910 JET BLACK', 112 => 'LS 615 CLASSICAL WHITE', 113 => 'LS 617 New EGGSHELL', 114 => 'LS 620 LIME WHITE', 115 => 'LS 621 SAND', 116 => 'LS 622 STONE', 117 => 'LS 032 SEA MIST', 118 => 'LS 049 STONE GREY', 119 => 'LS 051 BROWN GREY', 120 => 'LS 053 CLAY', 121 => 'LS 072 MATTINGLEY 267', 122 => 'LS 108 RUSTIC GREY', 123 => 'LS 109 WEATHERED TEAK', 124 => 'LS 110 CHIQUE WHITE', 125 => 'LS 114 TAUPE', 126 => 'LS 202 GOLDEN OAK', 127 => 'LS 204 OAK MANTEL', 128 => 'LS 205 GOLDENROD', 129 => 'LS 211 CHERRY', 130 => 'LS 212 DARK TEAK', 131 => 'LS 214 COCOA', 132 => 'LS 215 CORDOVAN', 133 => 'LS 219 MAHOGANY', 134 => 'LS 220 NEW EBONY', 135 => 'Top track', 136 => 'Track in Board', 137 => 'Green', 138 => 'Biowood', 139 => 'Supreme', 140 => 'P', 141 => 'H', 142 => '4009', 143 => 'Track in Board', 144 => 'Bottom M Track', 145 => 'Other', 146 => 'Bay Window Tier-on-Tier', 147 => '', 148 => '', 149 => '', 150 => 'L90', 151 => 'M Track', 152 => '50.8mm Butt Flat', 153 => '50.8mm Rebated Flat', 154 => '50.8mm Butt Beaded', 155 => '50.8mm Rebated Beaded', 156 => '50.8mm Astragal Flat', 157 => '50.8mm Astragal Beaded', 158 => '38.1mm Butt Flat', 159 => '38.1mm Rebated Flat', 160 => '38.1mm Butt Beaded', 161 => '38.1mm Rebated Beaded', 162 => '38.1mm Astragal Flat', 163 => '38.1mm Astragal Beaded', 164 => '50.8mm', 165 => '114.3mm', 166 => 'LS 221 BLACK WALNUT', 167 => 'Pearl', 168 => 'Nickel', 169 => 'Yes', 170 => 'No', 171 => 'P4028X', 172 => '', 173 => 'L50MF', 174 => 'Bisque', 175 => '', 176 => '', 177 => '', 178 => '', 179 => '', 180 => '', 181 => '', 182 => '', 183 => '', 184 => '', 185 => '', 186 => 'Hidden', 187 => 'Earth', 188 => 'Ecowood', 189 => '', 190 => '', 191 => '', 192 => '', 193 => '', 194 => '', 195 => '', 196 => '', 197 => '', 198 => '', 199 => '', 200 => '', 201 => '', 202 => '', 203 => '', 204 => '', 205 => '', 206 => '', 207 => '', 208 => '', 209 => '', 210 => '', 211 => '', 212 => '', 213 => '', 214 => '', 215 => '', 216 => 'E', 217 => '', 218 => '', 219 => '', 220 => 'LS 227 RED OAK', 221 => 'Solid Flat Panel', 222 => 'Solid Raised Panel', 223 => 'Hidden Tilt', 224 => 'Hidden Tilt Split', 225 => 'Café Style Bay Window', 226 => 'Solid Raised Café Style', 227 => 'Solid Flat Tier-on-Tier', 228 => 'Solid Raised Tier-on-Tier', 229 => 'Solid Combi Panel', 230 => '', 231 => '', 232 => '', 233 => '', 234 => '', 235 => '', 236 => '', 237 => '', 238 => '', 239 => '', 240 => '', 241 => '', 242 => '', 243 => '', 244 => '', 245 => '', 246 => '', 247 => '', 248 => '', 249 => '', 250 => '', 251 => '', 252 => '', 253 => 'LS 229 RICH WALNUT', 254 => 'LS 230 OLD TEAK', 255 => 'LS 232 RED MAHOGANY', 256 => 'LS 237 WENGE', 257 => 'LS 862 FRENCH OAK', 258 => 'A100 (WHITE )', 259 => 'A103 (PEARL)', 260 => 'A107( BLACK)', 261 => 'A108 (SILVER)', 262 => 'A202 (LIGHT CEDAR)', 263 => 'A203 (GOLDEN OAK )', 264 => 'P601 WHITE BRUSHED (+20%)', 265 => 'P603 VANILLA BRUSHED (+20%)', 266 => 'P630 WINTER WHITE BRUSHED (+20%)', 267 => 'P631 STONE BRUSHED (+20%)', 268 => 'P632 MISTRAL BRUSHED (+20%)', 269 => 'P615 CLASSICAL WHITE BRUSHED (+20%)', 270 => 'P910 JET BLACK BRUSHED (+20%)', 271 => 'P817 OLD TEAK BRUSHED (+20%)', 272 => 'P819 COFFEE BEAN BRUSHED (+20%)', 273 => 'PS-1 HONEY BRUSHED (+20%)', 274 => '', 275 => '', 276 => '', 277 => '', 278 => '', 279 => '', 280 => '', 281 => '', 282 => '', 283 => '', 284 => '', 285 => '', 286 => '', 287 => '', 288 => '', 289 => '', 290 => '', 291 => '', 300 => 'A4001A', 301 => 'A4028', 302 => 'A4027', 303 => '', 304 => '', 305 => '4001A', 306 => '4007A', 307 => '4008A', 308 => '4001B', 309 => '4007B', 310 => '4008B', 311 => '4001C', 312 => '4007C', 313 => '4008C', 314 => '4003', 315 => '4004', 316 => '4013', 317 => '4014', 318 => 'P4028B', 319 => 'P4008W', 320 => 'P4001N', 321 => 'P4008H', 322 => 'P4008T', 323 => 'P4008K', 324 => 'P4073', 325 => 'P4013', 326 => 'P4023', 327 => 'P4033', 328 => 'P4043', 329 => 'P4014', 330 => 'P4008S', 331 => 'P4007A', 332 => '4022B', 333 => '4028B', 350 => 'A1002B (Std.beaded stile)', 351 => 'P4022B', 352 => '', 353 => '', 354 => '', 355 => '1001B(51mm plain butt)', 356 => '1005B(51mm plain D-mould)', 357 => '1002B(51mm beaded butt)', 358 => '1006B(51mm beaded D-mould)', 359 => '1004B(51mm beaded rebate)', 360 => '1003B(51mm plain rebate)', 361 => '1001A(35mm plain butt)', 362 => '1005A(35mm plain D-mould)', 363 => '1002A(35mm beaded butt)', 364 => '1006A(35mm beaded D-mould)', 365 => '1004A(35mm beaded rebate)', 366 => '1003A(35mm plain rebate)', 367 => '', 368 => '', 369 => '', 370 => 'T1001K(51mm plain butt)', 371 => 'T1005K(51mm plain D-mould)', 372 => 'T1002K(51mm beaded butt)', 373 => 'T1006K(51mm beaded D-mould)', 374 => 'T1004K(51mm beaded rebate)', 375 => 'T1003K(51mm plain rebate)', 376 => '41mm T1001M(plain butt)', 377 => '41mm T1005M(plain D-mould)', 378 => '41mm T1003M(plain rebate)', 379 => '', 380 => 'PVC-P1001B(51mm plain butt)', 381 => 'PVC-P1005B(51mm plain D-mould)', 382 => 'PVC-P1002B(51mm beaded butt)', 383 => 'PVC-P1006B(51mm beaded D-mould)', 384 => 'PVC-P1004E(51mm beaded rebate)', 385 => 'PVC-P1003E(51mm  plain rebate)', 386 => '', 387 => '', 388 => '', 389 => '', 390 => '', 400 => 'Centre rod', 401 => 'Hidden rod', 402 => 'Offset tilt rod', 403 => 'Concealed rod', 404 => 'Frosted White', 405 => '101 - Snow White', 406 => 'K077 - Creamy', 407 => '616 - Champagne', 408 => '231 - Rusty Grey', 409 => '841 - Cherry', 410 => '310 - Roasted Coffee', 411 => 'Frosted White', 412 => 'Neutral White', 413 => 'Shell White', 414 => '', 415 => '', 416 => '', 417 => '', 418 => '', 419 => '', 420 => '', 421 => '', 422 => '', 423 => '', 424 => '', 425 => '', 426 => '', 427 => '', 428 => '', 429 => '', 430 => '', 431 => '', 432 => '', 433 => '', 434 => '', 435 => '', 436 => '', 437 => 'P7032', 438 => 'P7001', 439 => 'P7201', 440 => 'Black', 441 => '7011');
//
//  $atribute = get_post_meta( 1,'attributes_array',true );
//   update_post_meta( 1,'attributes_array',$atributezzz );
//   print_r($atribute);

$atributezzz = include('/public_html/wp-content/plugins/shutter-module/ajax/atributes_array.php');
global $wpdb;

//$all_atributes = $wpdb->get_results( "SELECT * FROM `wp_shutter_attributes`" );
//print_r($all_atributes);
$properties = array(3 => 'BattenStandard', 4 => 'BattenCustom', 52 => 'Flat Louver-%', 56 => 'Inside-%', 187 => 'Earth', 188 => 'Ecowood', 137 => 'Green', 138 => 'Biowood', 139 => 'Supreme', 221 => 'Solid-%', 229 => 'Combi-%', 33 => 'Shaped-%', 34 => 'French Door-+', 35 => 'Tracked-+', 37 => 'TrackedByPass-+', 36 => 'Arched-%', 100 => 'Buildout-%', 93 => 'Stainless Steel-%', 93 => 'Stainless Steel-%', 403 => 'Concealed Rod-%', 101 => 'Bay Angle-%', 264 => 'Colors-%', 102 => 'Ringpull-+', 103 => 'Spare Louvres-+', 186 => 'Hidden-%', 171 => 'P4028X-%', 319 => 'P4008W-%', 322 => 'P4008T-%', 275 => 'T_Buildout-%', 276 => 'B_Buildout-%', 277 => 'C_Buildout-%', 278 => 'Lock-+', 500 => 'G_post-%', 501 => 'blackoutblind-+', 2 => 'B_typeFlexible-%', 5 => 'T_typeAdjustable-%', 502 => 'tposttype_blackout-%', 73 => 'Light_block-%');

$propertie_price = array(3 => get_post_meta(1, 'BattenStandard', true), 4 => get_post_meta(1, 'BattenCustom', true), 52 => get_post_meta(1, 'Flat_Louver', true), 56 => get_post_meta(1, 'Inside', true), 187 => get_post_meta(1, 'Earth', true), 188 => get_post_meta(1, 'Ecowood', true), 137 => get_post_meta(1, 'Green', true), 138 => get_post_meta(1, 'Biowood', true), 139 => get_post_meta(1, 'Supreme', true), 221 => get_post_meta(1, 'Solid', true), 229 => get_post_meta(1, 'Combi', true), 171 => get_post_meta(1, 'P4028X', true), 319 => get_post_meta(1, 'P4008W', true), 322 => get_post_meta(1, 'P4008T', true), 33 => get_post_meta(1, 'Shaped', true), 34 => get_post_meta(1, 'French_Door', true), 35 => get_post_meta(1, 'Tracked', true), 37 => get_post_meta(1, 'TrackedByPass', true), 36 => get_post_meta(1, 'Arched', true), 100 => get_post_meta(1, 'Buildout', true), 93 => get_post_meta(1, 'Stainless_Steel', true), 403 => get_post_meta(1, 'Concealed_Rod', true), 101 => get_post_meta(1, 'Bay_Angle', true), 264 => get_post_meta(1, 'Colors', true), 102 => get_post_meta(1, 'Ringpull', true), 103 => get_post_meta(1, 'Spare_Louvres', true), 186 => get_post_meta(1, 'Hidden', true), 275 => get_post_meta(1, 'T_Buildout', true), 276 => get_post_meta(1, 'B_Buildout', true), 277 => get_post_meta(1, 'C_Buildout', true), 278 => get_post_meta(1, 'Lock', true), 500 => get_post_meta(1, 'G_post', true), 501 => get_post_meta(1, 'blackoutblind', true), 2 => get_post_meta(1, 'B_typeFlexible', true), 5 => get_post_meta(1, 'T_typeAdjustable', true), 502 => get_post_meta(1, 'tposttype_blackout', true), 73 => get_post_meta(1, 'Light_block', true));

$propertie_price_dolar = array(3 => get_post_meta(1, 'BattenStandard-dolar', true), 4 => get_post_meta(1, 'BattenCustom-dolar', true), 52 => get_post_meta(1, 'Flat_Louver-dolar', true), 56 => get_post_meta(1, 'Inside-dolar', true), 187 => get_post_meta(1, 'Earth-dolar', true), 188 => get_post_meta(1, 'Ecowood-dolar', true), 137 => get_post_meta(1, 'Green-dolar', true), 138 => get_post_meta(1, 'Biowood-dolar', true), 139 => get_post_meta(1, 'Supreme-dolar', true), 171 => get_post_meta(1, 'P4028X-dolar', true), 319 => get_post_meta(1, 'P4008W-dolar', true), 322 => get_post_meta(1, 'P4008T-dolar', true), 221 => get_post_meta(1, 'Solid-dolar', true), 229 => get_post_meta(1, 'Combi-dolar', true), 33 => get_post_meta(1, 'Shaped-dolar', true), 36 => get_post_meta(1, 'Arched-dolar', true), 34 => get_post_meta(1, 'French_Door-dolar', true), 35 => get_post_meta(1, 'Tracked-dolar', true), 37 => get_post_meta(1, 'TrackedByPass-dolar', true), 100 => get_post_meta(1, 'Buildout-dolar', true), 93 => get_post_meta(1, 'Stainless_Steel-dolar', true), 403 => get_post_meta(1, 'Concealed_Rod-dolar', true), 101 => get_post_meta(1, 'Bay_Angle-dolar', true), 264 => get_post_meta(1, 'Colors-dolar', true), 102 => get_post_meta(1, 'Ringpull-dolar', true), 186 => get_post_meta(1, 'Hidden-dolar', true), 103 => get_post_meta(1, 'Spare_Louvres-dolar', true), 275 => get_post_meta(1, 'T_Buildout-dolar', true), 276 => get_post_meta(1, 'B_Buildout-dolar', true), 277 => get_post_meta(1, 'C_Buildout-dolar', true), 278 => get_post_meta(1, 'Lock-dolar', true), 501 => get_post_meta(1, 'blackoutblind-dolar', true), 2 => get_post_meta(1, 'B_typeFlexible-dolar', true), 5 => get_post_meta(1, 'T_typeAdjustable-dolar', true), 502 => get_post_meta(1, 'tposttype_blackout-dolar', true), 73 => get_post_meta(1, 'Light_block-dolar', true));

?>

<div class="container-fluid">
    <h3>Shutter Settings</h3>

    <div class="container-fluid">

        <div class="jumbotron">

            <!--            <h3>Shortcodes:</h3>-->
            <!--            <ul>-->
            <!--                <li>- Shutter shortcode: [product_shutter1]</li>-->
            <!--                <li>- Individual Bay Shutters shortcode: [product_shutter2]</li>-->
            <!--                <li>- Shutter and Blackout Blind shortcode: [product_shutter3]</li>-->
            <!--                <li>- Blackout Frame shortcode: [product_shutter4]</li>-->
            <!--                <li>- Batten shortcode: [product_shutter5]</li>-->
            <!--            </ul>-->

            <h4>Customize Shutter Attributes:</h4>

            <br>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#home" aria-controls="home" role="tab" data-toggle="tab"> Pound </a>
                </li>
                <li role="presentation">
                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"> Dolar </a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">

                    <form action="" id="form-table-attributes" class="form-horizontal">
                        <table id="shutter-table-settings" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 0;
                            foreach ($properties as $id => $propertie) {
                                $i++;

                                $part = explode("-", $propertie);
                                $part[0]; // name
                                $part[1]; // ecuation

                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $propertie; ?>
                                    </td>
                                    <td>
                                                <span class="hidden">
                                                    <?php echo $id; ?>
                                                </span>
                                        <input type="text" class="form-control" style="width: 100%;"
                                               placeholder="Enter price" name="price-<?php echo $part[0]; ?>"
                                               value="<?php echo $propertie_price[$id]; ?>">
                                    </td>

                                </tr>
                                <?php
                            }
                            ?>

                            <tr>
                                <td class="text-center">
                                    <?php echo $i++; ?>
                                </td>
                                <td>
                                    <label for="train_price">Train price sq/m</label>
                                </td>
                                <td>
                                    <input name="train_price" type="text" id="train_price"
                                           value="<?php echo get_post_meta(1, 'train_price', true); ?>" placeholder="Enter price">
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </tfoot>
                        </table>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info btn-lg">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    <form action="" id="form-table-attributes-dolar" class="form-horizontal">
                        <table id="shutter-table-settings-dolar" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 0;
                            foreach ($properties as $id => $propertie) {
                                $i++;

                                $part = explode("-", $propertie);
                                $part[0]; // name
                                $part[1]; // ecuation

                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $propertie; ?>
                                    </td>
                                    <td>
                                                <span class="hidden">
                                                    <?php echo $id; ?>
                                                </span>
                                        <input type="text" class="form-control" style="width: 100%;"
                                               placeholder="Enter price" name="dolar_price-<?php echo $part[0]; ?>"
                                               value="<?php echo $propertie_price_dolar[$id]; ?>">
                                    </td>

                                </tr>
                                <?php

                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </tfoot>
                        </table>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info btn-lg submit-dolar">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="show-attributes">
        </div>
        <div class="show-attributes-dolar">
        </div>

    </div>

</div>

<script>
    jQuery.noConflict();
    (function ($) {
        $(function () {
            jQuery(document).ready(function () {

                // Set price for normal atributes
                jQuery("#form-table-attributes").submit(function (event) {
                    event.preventDefault();
                    console.log('lire');
                    var attributes = jQuery('#form-table-attributes').serialize();
                    //console.log(attributes);
                    $.ajax({
                        method: "POST",
                        url: "/wp-content/plugins/shutter-module/ajax/ajax-update-price.php",
                        data: {
                            attributes: attributes
                        }
                    })
                        .done(function (data) {
                            jQuery('.show-attributes').html(data);
                            alert("Data Saved: " + data);
                            console.log(data);

                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        });
                });

                // Set price for dolar attributes
                jQuery("#form-table-attributes-dolar").submit(function (event) {
                    event.preventDefault();
                    console.log('dolar');
                    var attributes = jQuery('#form-table-attributes-dolar').serialize();
                    //console.log(attributes);
                    $.ajax({
                        method: "POST",
                        url: "/wp-content/plugins/shutter-module/ajax/ajax-update-price-dolar.php",
                        data: {
                            attributes: attributes
                        }
                    })
                        .done(function (data) {
                            jQuery('.show-attributes-dolar').html(data);
                            alert("Data Saved: " + data);
                            console.log(data);

                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        });
                });

            });

        });
    })(jQuery)
</script>
