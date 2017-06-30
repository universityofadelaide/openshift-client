<?php

namespace UniversityOfAdelaide\OpenShift\Model;

class Secret extends Model
{

    protected $default = [
      'api_version' => 'v1',
      'kind' => 'Secret',
      'metadata' => [
        'name' => 'Unnamed',
      ],
      'type' => 'Opaque',
      'data' => 'Data',
    ];
}
