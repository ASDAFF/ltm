<?php

namespace Ltm\Domain\Service\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Mts\Domain\Service\Configuration\Provider;

class TemplateEngine extends AbstractConfiguration implements ConfigurationInterface
{
    public function __construct(Provider\ProviderInterface $provider)
    {
        parent::__construct($provider);
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root()->useAttributeAsKey('name')->prototype('scalar')->end();

        return $treeBuilder;
    }
}
