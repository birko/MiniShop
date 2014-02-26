<?php

namespace Core\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaSourceTranslationType extends MediaType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!empty($options['cultures']))
        {
            $builder->add('translations', 'collection', array(
                'type' => 'file',
                'allow_add' => false,
                'allow_delete' => false,
                'prototype' => false, 
                'by_reference' => false,
                'options' => array(
                    'required' => false,
                    'only_file' => true,
            )));
        }
    }
}

?>
