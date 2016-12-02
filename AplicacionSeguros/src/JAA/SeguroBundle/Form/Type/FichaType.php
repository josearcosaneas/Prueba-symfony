<?php
namespace JAA\SeguroBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FichaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text', array("label" => "Titulo: ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))
                
            ->add('description','textarea', array("label" => "Descripción: ",
                    "required" => false,
                    "attr" => array('class' => 'form-control')))
               
            // Campo de tipo entidad el solo saca los tipos de ficha asociados
            ->add('tipoficha', 'entity', array(
                "label" => "Tipo de Ficha: ",
                'class' => 'JAASeguroBundle:Tipoficha',
                "attr" => array('class' => 'form-control'),
                "property"=>"name"
            ))
               
                            
            ->add('valor','textarea', array("label" => "Valor: ",
                    "required" => true,
                    "attr" => array('class' => 'form-control')))
                

                
            ->add('Guardar', 'submit', array("attr" => array('class' => 'btn btn-success')));
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
