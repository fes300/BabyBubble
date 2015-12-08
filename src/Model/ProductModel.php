<?php

namespace Babybubble\Model;

class ProductModel {

    function __construct($data) {
        $this->uuid = empty($data['uuid']) ? '' : $data['uuid'];
        $this->name = empty($data['name']) ? null : $data['name'];
        $this->duration = empty($data['duration']) ? null : $data['duration'];
        $this->description = empty($data['description']) ? null : $data['description'];
        $this->created = empty($data['created']) ? null : $data['created'];
        $this->updated = empty($data['updated']) ? null : $data['updated'];
        $this->active = empty($data['active']) ? null : $data['active'];
    }
}
