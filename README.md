Pipe Rat
========

Create REST APIs with just a few lines of Expressive config. 
This PSR7 compliant PHP library that uses Zend\Expressive Middleware at its core.

## Concept ##

- Remove unneeded complexity. Take the simplicity of pipe-rat to the next level
- Use standard expressive config format
- Split repository into discrete parts (separate concerns and improve security) 

- Config example:
    
```php
'routes' => [
    /* might key on path for speed */
    '{pipe-rat-2-config.root-path}.{pipe-rat-2-config.resource-name}.find' => [
        /* Use standard route names for client simplicity */
        'name' => '{pipe-rat-2-config.root-path}.{pipe-rat-2-config.resource-name}.find',
        
        /* Use standard route paths for client simplicity */
        'path' => '{pipe-rat-2-config.root-path}/{pipe-rat-2-config.resource-name}',
        
        /* Wire each API independently */
        'middleware' => [
            RequestFormat::configKey()
            => RequestFormat::class,

            RequestAcl::configKey()
            => RequestAcl::class,

            RequestAttributes::configKey()
            => RequestAttributes::class,

            /** <response-mutators> */
            ResponseHeaders::configKey()
            => ResponseHeaders::class,

            ResponseFormat::configKey()
            => ResponseFormat::class,

            ResponseDataExtractor::configKey()
            => ResponseDataExtractor::class,
            /** </response-mutators> */

            RepositoryFind::configKey()
            => RepositoryFind::class,
        ],
        
        /* Use route to find options at runtime */
        'options' => [
            RequestFormat::configKey() => [
                RequestFormat::OPTION_SERVICE_NAME
                => WithParsedBodyJson::class,

                RequestFormat::OPTION_SERVICE_OPTIONS => [],
            ],

            RequestAcl::configKey() => [
                RequestAcl::OPTION_SERVICE_NAME
                => IsAllowedNotConfigured::class,

                RequestAcl::OPTION_SERVICE_OPTIONS => [
                    IsAllowedNotConfigured::OPTION_MESSAGE
                    => IsAllowedNotConfigured::DEFAULT_MESSAGE
                        . ' for pipe-rat-2 resource: "{pipe-rat-2-config.resource-name}"'
                        . ' in file: "{pipe-rat-2-config.source-config-file}"',
                ],
            ],

            RequestAttributes::configKey() => [
                RequestAttributes::OPTION_SERVICE_NAMES => [
                    WithRequestAttributeWhere::class
                    => WithRequestAttributeUrlEncodedWhere::class,

                    WithRequestAttributeWhereMutator::class
                    => WithRequestAttributeWhereMutatorNoop::class,

                    WithRequestAttributeFields::class
                    => WithRequestAttributeUrlEncodedFields::class,

                    WithRequestAttributeOrder::class
                    => WithRequestAttributeUrlEncodedOrder::class,

                    WithRequestAttributeSkip::class
                    => WithRequestAttributeUrlEncodedSkip::class,

                    WithRequestAttributeLimit::class
                    => WithRequestAttributeUrlEncodedLimit::class,
                ],

                RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                    WithRequestAttributeWhere::class => [
                        WithRequestAttributeUrlEncodedWhere::OPTION_ALLOW_DEEP_WHERES => false,
                    ]
                ],
            ],

            /** <response-mutators> */
            ResponseHeaders::configKey() => [
                ResponseHeaders::OPTION_SERVICE_NAME
                => WithResponseHeadersAdded::class,

                ResponseHeaders::OPTION_SERVICE_OPTIONS => [
                    WithResponseHeadersAdded::OPTION_HEADERS => []
                ],
            ],

            ResponseFormat::configKey() => [
                ResponseFormat::OPTION_SERVICE_NAME
                => WithFormattedResponseJson::class,

                ResponseFormat::OPTION_SERVICE_OPTIONS => [],
            ],

            ResponseDataExtractor::configKey() => [
                ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractCollectionPropertyGetter::class,
                ResponseDataExtractor::OPTION_SERVICE_OPTIONS => [
                    ExtractCollectionPropertyGetter::OPTION_PROPERTY_LIST => null,
                    ExtractCollectionPropertyGetter::OPTION_PROPERTY_DEPTH_LIMIT => 1,
                ],
            ],
            /** </response-mutators> */

            RepositoryFind::configKey() => [
                RepositoryFind::OPTION_SERVICE_NAME
                => Find::class,

                RepositoryFind::OPTION_SERVICE_OPTIONS => [
                    Find::OPTION_ENTITY_CLASS_NAME
                    => '{pipe-rat-2-config.entity-class}',
                ],
            ],
        ],
        
        /* Use to define allowed methods */
        'allowed_methods' => ['GET'],
    ],
],
```

