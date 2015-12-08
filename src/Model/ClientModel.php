<?php

namespace Babybubble\Model;

class ClientModel {

    function __construct($data) {
        $this->uuid = empty($data['uuid']) ? '' : $data['uuid'];
        $this->first_name = empty($data['first_name']) ? null : $data['first_name'];
        $this->last_name = empty($data['last_name']) ? null : $data['last_name'];
        $this->tutors = empty($data['tutors']) ? null : json_decode($data['tutors']);
        $this->address = empty($data['address']) ? null : $data['address'];
        $this->phone = empty($data['phone']) ? null : $data['phone'];
        $this->email = empty($data['email']) ? null : $data['email'];
        $this->birthday = empty($data['birthday']) ? null : $data['birthday'];
        $this->medical_conditions = empty($data['medical_conditions']) ? null : $data['medical_conditions'];
        $this->first_contact_info = empty($data['first_contact_info']) ? null : $data['first_contact_info'];
        $this->created = empty($data['created']) ? null : $data['created'];
        $this->updated = empty($data['updated']) ? null : $data['updated'];
    }
}
