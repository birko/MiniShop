<?php

namespace Site\CategoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CategoryController extends Controller
{
    
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('CoreCategoryBundle:Category')->findOneByHome(true);
        if(empty($category))
        {
             $category = $em->getRepository('CoreCategoryBundle:Category')->createQueryBuilder('c')->getQuery()->getFirstResult();
        }
        $page = $this->getRequest()->get("page", 1);
        $cpage = $this->getRequest()->get("cpage", 1);
        return $this->render('SiteCategoryBundle:Category:home.html.twig', array('category' => $category, 'page' => $page, 'cpage' => $cpage));
    }
    
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('CoreCategoryBundle:Category')->findOneBySlug($slug);
        if(empty($category))
        {
             return $this->createNotFoundException(); 
        }
        $page = $this->getRequest()->get("page", 1);
        $cpage = $this->getRequest()->get("cpage", 1);
        return $this->render('SiteCategoryBundle:Category:index.html.twig', array('category' => $category, 'page' => $page, 'cpage' => $cpage));
    }
    
    public function listAction($menu, $parent = null)
    {
        $em = $this->getDoctrine()->getManager();
        $menu_index = $menu;
        if(!is_integer($menu_index))
        {
            $menus = $this->container->getParameter('menu');
            $menu_index = array_search($menu, $menus);
        }
        if($menu_index === false)
        {
            throw $this->createNotFoundException("Menu not found: ". $menu);
        }
        $categories = $em->getRepository('CoreCategoryBundle:Category')->getCategoriesByMenu($menu_index, $parent);
        return $this->render('SiteCategoryBundle:Category:list.html.twig', array(
            'categories' => $categories, 
            'id' => 'jcarousel'
            ));
    }
    
    public function treeAction($menu  = 0, $category = null)
    {
        $menu_index = $menu;
        $menus = $this->container->getParameter('menu');
        if(!is_numeric($menu_index))
        {
            $menu_index = array_search($menu, $menus);
        }
        if($menu_index === false || (count($menus) <= $menu_index))
        {
            throw $this->createNotFoundException("Menu not found: ". $menu);
        }
        else
        {
            $em = $this->getDoctrine()->getManager();
            $controller = $this; // $this cannot be used
            $options = array(
                'decorate' => true,
                'rootOpen' => $controller->renderView("SiteCategoryBundle:Category:Tree/rootOpen.html.twig"),
                'rootClose' => $controller->renderView("SiteCategoryBundle:Category:Tree/rootClose.html.twig"),
                'childOpen' =>  function($node) use ($controller, $category)
                                {
                                    $active = false;
                                    $last = false;
                                    if($category != null)
                                    {
                                        if($category->getId() == $node['id'])
                                        {
                                            $active = true;
                                            $last = true;
                                        }
                                        else if($category->getLeft() >= $node['lft'] && $category->getRight() <= $node['rgt'])
                                        {
                                            $active = true;
                                        }
                                    }
                                    return $controller->renderView("SiteCategoryBundle:Category:Tree/childOpen.html.twig", array('node'=> $node, 'active' => $active, 'last'=> $last));
                                },
                'childClose' => $controller->renderView("SiteCategoryBundle:Category:Tree/childClose.html.twig"),
                'nodeDecorator' =>  function($node) use ($controller) 
                                    {
                                        return $controller->renderView("SiteCategoryBundle:Category:Tree/nodeDecoration.html.twig", array("node" => $node));
                                    },
            );
            $parentsQueryBuilder = $em->getRepository('CoreCategoryBundle:Category')->getTreeQueryBuilder($menu_index);
            $query = $parentsQueryBuilder->getQuery();
            $parents = $query->getArrayResult();  
            $tree = (count($parents)> 0) ? $em->getRepository('CoreCategoryBundle:Category')->buildTree($parents, $options): "";
            return $this->render("SiteCategoryBundle:Category:Tree/tree.html.twig", array(
                'tree' => $tree,
            ));
        }
    }
    
    public function breadcrumbAction($id = null, $end = null, $nopath = false)
    {
        $path = array();
        $category = null;
        if(empty($id))
        {
            $id = $this->getRequest()->getSession()->get('breadcrumb_id', null);
        }
        $section  = null;
        if(!empty($id))
        {
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('CoreCategoryBundle:Category')->find($id);
            $menu = $this->container->getParameter('menu');
            $section = $menu[$category->getMenu()];
            if(!$nopath)
            {
                $this->getRequest()->getSession()->set('breadcrumb_id', $id);
                $path = $em->getRepository('CoreCategoryBundle:Category')->getPath($category);
            }
            else
            {
                $category = null;
            }
        }
       return $this->render('SiteCategoryBundle:Category:breadcrumb.html.twig', array(
            'path' => $path,
            'section' => $section,
            'category' => $category,
            'end' => $end
        ));
    }
}
