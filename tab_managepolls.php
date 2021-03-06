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

function print_action($action, $url) {
    global $OUTPUT;
    return html_writer::link($url, $OUTPUT->pix_icon("t/$action", "$action"));
}

$edit = get_string('edit');
$delete = get_string('delete');
$view = get_string('view');

$polls = $DB->get_records('block_poll', array('courseid' => $COURSE->id));

//TODO: Use html_table
$table = new html_table();
$table->head = array(get_string('editpollname', 'block_poll'),
             get_string('editpolloptions', 'block_poll'),
             get_string('responses', 'block_poll'),
             get_string('action'));
$table->align = array('left', 'right', 'right', 'left');
$table->tablealign = 'left';
$table->width = '*';

if ($polls !== false) {
    foreach ($polls as $poll) {
        $options = $DB->get_records('block_poll_option', array('pollid' => $poll->id));
        $responses = $DB->get_records('block_poll_response', array('pollid' => $poll->id));

        $url_preview = clone $url;
        $url_preview->params(array('action' => 'responses', 'pid' => $poll->id));
        $url_edit = clone $url;
        $url_edit->params(array('action' => 'editpoll', 'pid' => $poll->id));
        $url_delete = new moodle_url('/blocks/poll/poll_action.php', array('action' => 'delete', 'id' => $cid, 'pid' => $poll->id, 'instanceid' => $instanceid));

        $action = print_action('preview', $url_preview) .
                  print_action('edit', $url_edit) .
                  print_action('delete', $url_delete);
        $table->data[] = array($poll->name, (!$options ? '0' : count($options)), (!$responses ? '0' : count($responses)), $action);
    }
}

echo html_writer::table($table);
