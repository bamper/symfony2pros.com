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
            new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // our bundles
            new Proton\UserBundle\ProtonUserBundle(),
            new Proton\FrontendBundle\ProtonFrontendBundle(),
            new Proton\TutorialBundle\ProtonTutorialBundle(),
            new Proton\QnABundle\ProtonQnABundle(),
            new Proton\CommentBundle\ProtonCommentBundle(),
            new Proton\CoreBundle\ProtonCoreBundle(),
            new Proton\TagBundle\ProtonTagBundle(),

            // 3rd-party bundles
            new FOS\UserBundle\FOSUserBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),
            new Ornicar\GravatarBundle\OrnicarGravatarBundle(),
            new Knp\Bundle\TimeBundle\KnpTimeBundle(),
            new Snc\RedisBundle\SncRedisBundle(),
            new FPN\TagBundle\FPNTagBundle(),
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
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
