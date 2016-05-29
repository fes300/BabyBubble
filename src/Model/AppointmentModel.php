<?php

namespace Babybubble\Model;

class AppointmentModel {
    function __construct($data) {
        $this->uuid = empty($data['uuid']) ? '' : $data['uuid'];
        $this->client_uuid = empty($data['client_uuid']) ? null : $data['client_uuid'];
        $this->client_name = empty($data['client_name']) ? null : $data['client_name'];
        $this->product_uuid = empty($data['product_uuid']) ? null : $data['product_uuid'];
        $this->product_name = empty($data['product_name']) ? null : $data['product_name'];
        $this->product_duration = empty($data['product_duration']) ? null : $data['product_duration'];
        $this->date = empty($data['date']) ? null : $data['date'];
        $this->notes = empty($data['notes']) ? null : $data['notes'];
        $this->all_day = empty($data['all_day']) ? null : $data['all_day'];
        $this->created = empty($data['created']) ? null : $data['created'];
        $this->updated = empty($data['updated']) ? null : $data['updated'];
    }
}
