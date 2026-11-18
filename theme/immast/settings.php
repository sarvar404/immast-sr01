<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * settings.php
 *
 * @package   theme_immast
 * @copyright 2015 LMSACE Dev Team, lmsace.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
$settings = null;

if (is_siteadmin()) {

    $settings = new theme_boost_admin_settingspage_tabs('themesettingimmast', get_string('configtitle', 'theme_immast'));
    $ADMIN->add('themes', new admin_category('theme_immast', 'IMMAST Theme'));

    /* Header Settings */
    $temp = new admin_settingpage('theme_immast_header', get_string('generalheading', 'theme_immast'));

    // Logo file setting.
    $name = 'theme_immast/logo';
    $title = get_string('logo', 'theme_immast');
    $description = get_string('logodesc', 'theme_immast');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Custom CSS file.
    $name = 'theme_immast/customcss';
    $title = get_string('customcss', 'theme_immast');
    $description = get_string('customcssdesc', 'theme_immast');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $settings->add($temp);

    /* Front Page Settings */
    $temp = new admin_settingpage('theme_immast_frontpage', get_string('frontpageheading', 'theme_immast'));

     // Who we are title.
    $name = 'theme_immast/whoweare_title';
    $title = get_string('whoweare_title', 'theme_immast');
    $description = '';
    $default = get_string('whoweare_title_default', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

     // Who we are content.
    $name = 'theme_immast/whoweare_description';
    $title = get_string('whoweare_description', 'theme_immast');
    $description = get_string('whowearedesc', 'theme_immast');
    $default = get_string('whowearedefault', 'theme_immast');
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $temp->add($setting);

    $settings->add($temp);

    /* Slideshow Settings Start */
    $temp = new admin_settingpage('theme_immast_slideshow', get_string('slideshowheading', 'theme_immast'));
    $temp->add(new admin_setting_heading('theme_immast_slideshow', get_string('slideshowheadingsub', 'theme_immast'),
        format_text(get_string('slideshowdesc', 'theme_immast'), FORMAT_MARKDOWN)));

    // Display Slideshow.
    $name = 'theme_immast/toggleslideshow';
    $title = get_string('toggleslideshow', 'theme_immast');
    $description = get_string('toggleslideshowdesc', 'theme_immast');
    $yes = get_string('yes');
    $no = get_string('no');
    $default = 1;
    $choices = array(1 => $yes , 0 => $no);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);

    // Number of slides.
    $name = 'theme_immast/numberofslides';
    $title = get_string('numberofslides', 'theme_immast');
    $description = get_string('numberofslides_desc', 'theme_immast');
    $default = 1;
    $choices = array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => '11',
        12 => '12',
    );
    $temp->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    $numberofslides = get_config('theme_immast', 'numberofslides');
    for ($i = 1; $i <= $numberofslides; $i++) {

        // This is the descriptor for Slide One.
        $name = 'theme_immast/slide' . $i . 'info';
        $heading = get_string('slideno', 'theme_immast', array('slide' => $i));
        $information = get_string('slidenodesc', 'theme_immast', array('slide' => $i));
        $setting = new admin_setting_heading($name, $heading, $information);
        $temp->add($setting);

        // Slide Image.
        $name = 'theme_immast/slide' . $i . 'image';
        $title = get_string('slideimage', 'theme_immast');
        $description = get_string('slideimagedesc', 'theme_immast');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide' . $i . 'image');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Slide Caption.
        $name = 'theme_immast/slide' . $i . 'caption';
        $title = get_string('slidecaption', 'theme_immast');
        $description = get_string('slidecaptiondesc', 'theme_immast');
        $default = get_string('slidecaptiondefault', 'theme_immast', array('slideno' => sprintf('%02d', $i) ));
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $temp->add($setting);

        // Slider button.
        $name = 'theme_immast/slide' . $i . 'urltext';
        $title = get_string('slidebutton', 'theme_immast');
        $description = get_string('slidebuttondesc', 'theme_immast');
        $default = 'lang:knowmore';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $temp->add($setting);

        // Slide Description Text.
        $name = 'theme_immast/slide' . $i . 'url';
        $title = get_string('slideurl', 'theme_immast');
        $description = get_string('slideurldesc', 'theme_immast');
        $default = 'http://www.example.com/';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
        $temp->add($setting);

    }

    /* Slideshow Settings End*/

    $settings->add($temp);

    /* Footer Settings start */
    $temp = new admin_settingpage('theme_immast_footer', get_string('footerheading', 'theme_immast'));

    // Footer Logo file setting.
    $name = 'theme_immast/footerlogo';
    $title = get_string('footerlogo', 'theme_immast');
    $description = get_string('footerlogodesc', 'theme_immast');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'footerlogo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    /* Footer Content */
    $name = 'theme_immast/footnote';
    $title = get_string('footnote', 'theme_immast');
    $description = get_string('footnotedesc', 'theme_immast');
    $default = get_string('footnotedefault', 'theme_immast');
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $temp->add($setting);

    // INFO Link.
    $name = 'theme_immast/infolink';
    $title = get_string('infolink', 'theme_immast');
    $description = get_string('infolink_desc', 'theme_immast');
    $default = get_string('infolinkdefault', 'theme_immast');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $temp->add($setting);

    // Copyright.
    $name = 'theme_immast/copyright_footer';
    $title = get_string('copyright_footer', 'theme_immast');
    $description = '';
    $default = get_string('copyright_default', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    /* Address , Email , Phone No */
    $name = 'theme_immast/address';
    $title = get_string('address', 'theme_immast');
    $description = '';
    $default = get_string('defaultaddress', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_immast/emailid';
    $title = get_string('emailid', 'theme_immast');
    $description = '';
    $default = get_string('defaultemailid', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_immast/phoneno';
    $title = get_string('phoneno', 'theme_immast');
    $description = '';
    $default = get_string('defaultphoneno', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    /* Facebook, Pinterest, Twitter, Google+ Settings */
    $name = 'theme_immast/fburl';
    $title = get_string('fburl', 'theme_immast');
    $description = get_string('fburldesc', 'theme_immast');
    $default = get_string('fburl_default', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_immast/pinurl';
    $title = get_string('pinurl', 'theme_immast');
    $description = get_string('pinurldesc', 'theme_immast');
    $default = get_string('pinurl_default', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_immast/twurl';
    $title = get_string('twurl', 'theme_immast');
    $description = get_string('twurldesc', 'theme_immast');
    $default = get_string('twurl_default', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_immast/gpurl';
    $title = get_string('gpurl', 'theme_immast');
    $description = get_string('gpurldesc', 'theme_immast');
    $default = get_string('gpurl_default', 'theme_immast');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $settings->add($temp);
    /*  Footer Settings end */
}
