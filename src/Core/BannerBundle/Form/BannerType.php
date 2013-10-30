<?php

namespace Core\BannerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Core\MediaBundle\Form\MediaType;
use Core\MediaBundle\Form\ImageType;



class BannerType extends EditBannerType
{
    protected $mediaType = 'ImageType';
    public function __construct($mediaType = 'ImageType')
    {
        $this->mediaType = $mediaType;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        switch($this->mediaType)
        {
            case 'ImageType':
            default:
            $type = new ImageType();
            break;
        }
        $builder
            ->add('media', $type, array('required' => false))
        ;
    }
}
