<?php

namespace UniversityOfAdelaide\OpenShift\Model;

class BuildConfig extends Model
{
    const DEFAULT = [
      'kind' => 'BuildConfig',
      'metadata' => [
        'annotations' => [
          'description' => 'Defines how to build the application',
        ],
        'name' => 'Unnamed',
      ],
      'spec' => [
        'output' => [
          'to' => [
            'kind' => 'ImageStreamTag',
            'name' => 'imagestreamtag',
          ],
        ],
        'source' => [
          'type' => 'Git',
          'git' => [
            'ref' => 'master',
            'uri' => 'git@github.com:universityofadelaide/project.git',
          ],
          'secrets' => [
            [
              'destinationDir' => '.',
              'secret' => [
                'name' => 'secret_name',
              ],
            ],
          ],
          'sourceSecret' => [
            'name' => 'secret_name',
          ],
        ],
        'strategy' => [
          'sourceStrategy' => [
            'incremental' => true,
            'from' => [
              'kind' => 'DockerImage',
              'name' => 'image',
            ],
            'pullSecret' => [
              'name' => 'secret_name',
            ],
          ],
          'type' => 'Source',
        ],
          // @todo - figure out github and other types of triggers
        'triggers' => [
          [
            'type' => 'ImageChange',
          ],
          [
            'type' => 'ConfigChange',
          ],
        ],
      ],
    ];
}
