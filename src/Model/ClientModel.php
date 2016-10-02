<?php

namespace Babybubble\Model;

class ClientModel {

    function __construct($data) {
        $this->uuid = !empty($data['uuid']) ? $data['uuid'] : '';
        $this->first_name = !empty($data['first_name']) ? $data['first_name'] : null;
        $this->last_name = !empty($data['last_name']) ? $data['last_name'] : null;
        $this->tutors = !empty($data['tutors']) ? json_decode($data['tutors']) : null;
        $this->address = !empty($data['address']) ? $data['address'] : null;
        $this->phone = !empty($data['phone']) ? $data['phone'] : null;
        $this->email = !empty($data['email']) ? $data['email'] : null;
        $this->birthday = !empty($data['birthday']) ? $data['birthday'] : null;
        $this->medical_conditions = !empty($data['medical_conditions']) ? $data['medical_conditions'] : null;
        $this->first_contact_info = empty($data['first_contact_info']) ? null : $data['first_contact_info'];
        $this->created = empty($data['created']) ? null : $data['created'];
        $this->updated = empty($data['updated']) ? null : $data['updated'];
        $this->active = empty($data['active']) === null ? true : $data['active'];
    }
}
