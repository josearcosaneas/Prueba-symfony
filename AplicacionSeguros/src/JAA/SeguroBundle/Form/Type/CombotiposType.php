<?php
namespace JAA\SeguroBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CombotiposType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoficha', 'entity', array(
                "label" => "Filtrar por tipo de ficha: ",
                'class' => 'JAASeguroBundle:Tipoficha',
                "attr" => array('class' => 'form-control'),
                "property"=>"name"
            ))
            ->add('Buscar', 'submit', array("attr" => array('class' => 'btn btn-success')));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JAA\SeguroBundle\Entity\Ficha'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jaa_segurobundle_ficha';
    }
}
