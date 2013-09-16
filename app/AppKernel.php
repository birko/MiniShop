<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(), 
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new WhiteOctober\TCPDFBundle\WhiteOctoberTCPDFBundle(),

            
            new Core\CategoryBundle\CoreCategoryBundle(),
            new Core\ContentBundle\CoreContentBundle(),
            new Core\ProductBundle\CoreProductBundle(),
            new Core\ShopBundle\CoreShopBundle(),
            new Core\MediaBundle\CoreMediaBundle(),
            new Core\CommonBundle\CoreCommonBundle(),
            new Core\UserBundle\CoreUserBundle(),
            new Core\VendorBundle\CoreVendorBundle(),
            new Site\ShopBundle\SiteShopBundle(),
            new Site\CategoryBundle\SiteCategoryBundle(),
            new Site\ContentBundle\SiteContentBundle(),
            new Site\ProductBundle\SiteProductBundle(),
            new Site\UserBundle\SiteUserBundle(),
            new Core\NewsletterBundle\CoreNewsletterBundle(),
            new Site\NewsletterBundle\SiteNewsletterBundle(),
            new Site\DefaultBundle\SiteDefaultBundle(),
            new Core\UserTextBundle\CoreUserTextBundle(),
            new Core\MarketingBundle\CoreMarketingBundle(),
            new Site\VendorBundle\SiteVendorBundle(),
            new Site\MarketingBundle\SiteMarketingBundle(),
            new Core\PriceBundle\CorePriceBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
