<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    //   Liste des produits dans un tableau
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Le repository est à appeler systématiquement lorsque l'on souhaite récupérer des données provent de la bdd
        // (Le repository de l'entité concernée)

        $products = $productRepository->findAll();

        dump($products);


        return $this->render('product/index.html.twig', [
            'products' => $products

        ]);
    }


    //  detail d'un produit, on injecte le repository ainsi que l'id passé en paramètre
    // pour pouvoir récupérer le detail du produit grace à la méthode find($id) equivalent
    //à SELECT * FROM product WHERE id=$id
    #[Route('/productDetail/{id}', name: 'productDetail')]
    public function productDetail(ProductRepository $productRepository, $id): Response
    {
         $product=$productRepository->find($id);

        return $this->render('product/productDetail.html.twig', [
          'product'=>$product
        ]);
    }


    // ajout d'un produit
    #[Route('/form', name: 'form')]
    public function form(Request $request, EntityManagerInterface $manager): Response
    {
        // cette méthode a pour but de traiter l'ajout de produit.
        // préalablement, nous avons configuré les labels, placeholder etc....
        // dans le formulaire de product (src/Form/ProductType.php)
        // ainsi que nous avons renseigné dans le fichier twig.yaml (config/packages/twig.yaml) la necessité d'utiliser bootstrap
        // dans nos vues de formulaire grâce à
        // form_themes: ['bootstrap_5_layout.html.twig']


        $product = new Product();
        dump($product);
        // on instancie un nouvel objet vide de la classe Product afin de le remplir via les saisies receptionnées du formulaire
        //

        // la méthode createForm() génère un objet de formulaire , elle attend plusieurs informations en argument:
        //1er: le formulaire sur lequel on se base, ici le ProductType
        //2nd: l'instance de classe: l'objet à remplir grace à ce formulaire
        // 3e: optionnel: un tableau d'options
        // chaques add() des formulaires (Types) doit correspondre à une propriété de l'entité en liens
        $form = $this->createForm(ProductType::class, $product, ['create'=>true]);

        // la méthode handleRequest() permet à symfony de remplir l'objet $product des informations provenants du formulaire
        $form->handleRequest($request);
        dump($product);

        // condition de soumission de formulaire, l'ordre doit être respecté

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setPublishDate(new \DateTime());
            // on récupère toutes les données sur l'input type file (picture)
            $picture = $form->get('picture')->getData();

            dump($picture);

            // renommage du fichier
            //  $picture->getClientOriginalName() permet de récupérer le nom du fichier en cours d'upload avec son extension
            $picture_bdd = date("YmdHis") . '-' . $picture->getClientOriginalName();

            $product->setPicture($picture_bdd);

            // on déplace le fichier temporaire dans notre dossier d'upload
            // Préalablement on créé une constante dans le fichier services.yaml (config/services.yaml) pour indiquer l'emplacement
            // de notre dossier d'upload

            // La méthode move permet de déplacer un fichier
            //1er argument: l'emplacement de copie
            //2nd argument: le nom du fichier à créer
            $picture->move($this->getParameter('upload_dir'), $picture_bdd);

            // on utilise $mager de la classe EntityManagerInterface pour toutes requête d'insertion,
            //de modification et de suppression

            // on demande au manager de préparer la requete
            $manager->persist($product);

            // on le demande à présent d'executer
            $manager->flush();

            return $this->redirectToRoute('home');


        }


        // on renvoie la vue de ce formulaire grace à la méthode de notre objet form, createView()
        return $this->render('product/form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    // modification d'un produit
    #[Route('/updateProduct/{id}', name: 'updateProduct')]
    public function updateProduct(Request $request,EntityManagerInterface $manager, Product $product): Response
    {

       // dd($product);

        $form=$this->createForm(ProductType::class, $product, ['update'=>true] );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
              $updatePicture=$form->get('updatePicture')->getData();



              // si une photo a été saisie sur notre input 'updatePicture' alors on renomme le fichier photo, on upload le fichier photo et on supprime le précédent fichier enfin on reaffecte le nouveau fichier photo à notre objet $product
              if ($updatePicture)
              {
                  // on renomme
                  $updatePicture_bdd=date('YmdHis').'-'.$updatePicture->getClientOriginalName();
                  //on upload
                  $updatePicture->move($this->getParameter('upload_dir'), $updatePicture_bdd);
                  // on supprime le précedent fichier photo grace à la méthode unlink() qui attend en param l'emplacement du fichier avec nom (que l'on récupère sur notre objet $product)
                  unlink($this->getParameter('upload_dir').'/'.$product->getPicture());
                  // on reaffecte à $product son nouveau fichier photo
                  $product->setPicture($updatePicture_bdd);


              }

              $manager->persist($product);

              $manager->flush();

              return $this->redirectToRoute('home');



        }







        return $this->render('product/updateProduct.html.twig', [
          'form'=>$form,
           'product'=>$product
        ]);
    }


    // suppression d'un produit
    #[Route('/deleteProduct/{id}', name: 'deleteProduct')]
    public function deleteProduct(Product $product, EntityManagerInterface $manager): Response
    {
        // methode du manager pour préparer une requete de suppression
            $manager->remove($product);
            $manager->flush();

        return $this->redirectToRoute('home');
    }



}
