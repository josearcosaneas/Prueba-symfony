<?php 
namespace JAA\SeguroBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class ResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

         $builder->add('email', 'email');
				
				/*->add('Enviar', 'submit',array("attr" => array('class' => 'btn btn-success')));
				*/
               
    }

    public function getName()
    {
        return 'reset';
    }
}

?>
