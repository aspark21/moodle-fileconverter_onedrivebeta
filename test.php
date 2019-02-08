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
 * Test that Microsoft OneDrive is configured correctly
 *
 * @package   fileconverter_onedrivebeta
 * @copyright 2018 University of Nottingham
 * @author    Neill Magill <neill.magill@nottingham.ac.uk>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/filelib.php');

use core\notification;

$sendpdf = optional_param('sendpdf', 0, PARAM_BOOL);

$PAGE->set_url(new moodle_url('/files/converter/onedrivebeta/test.php'));
$PAGE->set_context(context_system::instance());

require_login();
require_capability('moodle/site:config', context_system::instance());

$strheading = get_string('test_conversion', 'fileconverter_onedrivebeta');
$PAGE->navbar->add(get_string('administrationsite'));
$PAGE->navbar->add(get_string('plugins', 'admin'));
$PAGE->navbar->add(get_string('pluginname', 'fileconverter_onedrivebeta'),
        new moodle_url('/admin/settings.php', array('section' => 'fileconverteronedrivebeta')));
$PAGE->navbar->add($strheading);
$PAGE->set_heading($strheading);
$PAGE->set_title($strheading);

$converter = new \fileconverter_onedrivebeta\converter();

if ($sendpdf) {
    require_sesskey();

    $converter->serve_test_document();
    die();
}

$result = $converter->are_requirements_met();
if ($result) {
    $msg = $OUTPUT->notification(get_string('test_conversionready', 'fileconverter_onedrivebeta'), notification::SUCCESS);
    $pdflink = new moodle_url($PAGE->url, array('sendpdf' => 1, 'sesskey' => sesskey()));
    $msg .= html_writer::link($pdflink, get_string('test_conversion', 'fileconverter_onedrivebeta'));
    $msg .= html_writer::empty_tag('br');
} else {
    // Diagnostics time.
    $issuerid = get_config('fileconverter_onedrivebeta', 'issuerid');
    if (empty($issuerid)) {
        $problem = get_string('test_issuernotset', 'fileconverter_onedrivebeta');
    } else {
        $issuer = \core\oauth2\api::get_issuer($issuerid);
        if (empty($issuer)) {
            $problem = get_string('test_issuerinvalid', 'fileconverter_onedrivebeta');
        } else if (!$issuer->get('enabled')) {
            $problem = get_string('test_issuernotenabled', 'fileconverter_onedrivebeta');
        } else if (!$issuer->is_system_account_connected()) {
            $problem = get_string('test_issuernotconnected', 'fileconverter_onedrivebeta');
        } else {
            $problem = get_string('test_conversionnotready', 'fileconverter_onedrivebeta');
        }
    }
    $msg = $OUTPUT->notification($problem, notification::WARNING);
}
$returl = new moodle_url('/admin/settings.php', array('section' => 'fileconverteronedrivebeta'));
$msg .= $OUTPUT->continue_button($returl);

echo $OUTPUT->header();
echo $OUTPUT->box($msg, 'generalbox');
echo $OUTPUT->footer();
