<?php

    namespace GSB\VisiteurBundle\Controller;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use GSB\VisiteurBundle\Entity\FraisForfait;
    use Doctrine\ORM\FraisForfaitRepository;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\FormType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\ResetType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use GSB\VisiteurBundle\Form\FraisForfaitType;

    class FraisForfaitController extends Controller{

        public function fraisforfaitAction(Request $query){

            $fraisforfait = new FraisForfait();// creer un objet Candidat

            $form = $this->createForm(FraisForfaitType::class, $fraisforfait);
            if ($query->isMethod('POST')) {
                $form->handleRequest($query);

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($fraisforfait);
                    $em->flush();
                    $query->getSession()->getFlashBag()->add('notice', 'Frais forfait enregistré.');
                    return new Response("le frais forfait a été crée.");

                }
            }
            return $this->render('GSBVisiteurBundle:Visiteur:vueFraisForfait.html.twig',// en cas d'érreur on revient au formulaire
            array('form' => $form->createView(),));
        }
    }
