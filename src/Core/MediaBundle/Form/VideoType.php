<?php

namespace Core\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Core\MediaBundle\Entity\VideoType as VideoTypes;


class VideoType extends EditVideoType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('videoType', 'choice', array(
                'required'    => true,
                'choices' => VideoTypes::getTypes(),
                'label' =>  'Video type'
                
        ));
        $builder->add('source', 'text', array(
                'required'    => false
        ));
        $builder->add('file', 'file', array(
                'required'    => false
        ));
        parent::buildForm($builder, $options);
    }
}

?>
