<?php

return [

    'audit-log'           => [
        'category'              => 'Activities',
        'msg-index'             => 'Accessed list of activities.',
        'msg-show'              => 'Accessed details of activity: :name.',
        'msg-store'             => 'Created new task: :name.',
        'msg-edit'              => 'Initiated edit of activity: :name.',
        'msg-update'            => 'Submitted edit of activity: :name.',
        'msg-destroy'           => 'Deleted activity: :name.',
        'msg-enable'            => 'Enabled activity: :name.',
        'msg-disabled'          => 'Disabled activity: :name.',
        'msg-enabled-selected'  => 'Enabled multiple activity.',
        'msg-disabled-selected' => 'Disabled multiple activity.',
    ],

    'status'              => [
        'created'                   => 'Activities successfully created',
        'updated'                   => 'Activities successfully updated',
        'deleted'                   => 'Activities successfully deleted',
        'global-enabled'            => 'Selected tasks enabled.',
        'global-disabled'           => 'Selected tasks disabled.',
        'enabled'                   => 'Task enabled.',
        'disabled'                  => 'Task disabled.',
        'no-task-selected'          => 'No task selected.',
    ],

    'error'               => [
        'cant-delete-this-task' => 'This activity cannot be deleted',
        'cant-edit-this-task'   => 'This activity cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Activities',
            'description'       => 'List of activities',
            'table-title'       => 'Activities list',
        ],
        'show'              => [
            'title'             => 'Admin | Activities | Show',
            'description'       => 'Displaying activity: :name',
            'section-title'     => 'Activities details',
        ],
        'create'            => [
            'title'            => 'Admin | Activities | Create',
            'description'      => 'Creating a new activity',
            'section-title'    => 'New activity',
        ],
        'edit'              => [
            'title'            => 'Admin | Activities | Edit',
            'description'      => 'Editing activity: :name',
            'section-title'    => 'Edit activity',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'ticket'                      => 'Ticket',
        'last_updated'                 =>  'Last Updated',
        'subject'                   =>  'Subject',
        'from'                   => 'From',
        'status'                   =>  'Status',
        'user'                      =>  'User',
        'darta_number'=>'Darta Number',
        'received_letter_number'=>'Received Letter Number',
        'letter_date'=>'Letter Date',
        'received_date'=>'Received Date',
        'sending_office_name'=>'Sending Office Name',
        'receiving_office_name'=>'Receiving Officer Name',
        'remarks'=>'Remarks',
        'phone_number'=>'Phone Number',
        'action'=>'Action',
    ],

    'button'               => [
        'create'    =>  'Create',
        'create_ticket'    =>  'Create new Ticket',
        'search'    =>  'Search',
        'clear'    =>  'Clear',
        'update'=>'Update',

    ],

    'placeholder'=>[
        'search'=>'Type to search..',
        'send_response'=>'Send Response to Client',
        'select'=>'Select',
        'detail_reason'=>'Details Reason for Opening Tickets',

    ],

    'form_header'=>[
        'user_and_callaborations'=>'User and Collaborators',
        'ticket_info_option'=>'Ticket Information and Options',
        'ticket_detail'=>'Ticket Details:',
        'issue_describe'=>'Please Describe Your Issue',
        'add_more_file'=>'Add More Files',
        'ticket_threads'=>'Ticket Threads',
        'response'=>'Response',

    ],

];