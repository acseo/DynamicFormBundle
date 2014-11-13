Getting Started With ACSEODynamicFormBundle
==================================

## Installation

1. Download ACSEODynamicFormBundle using composer
2. Enable the Bundle
3. Controller example
4. Create your own provider

### Step 1: Download ACSEODynamicFormBundle using composer

Add ACSEODynamicFormBundle in your composer.json:

```json
{	
    "require": {
        "acseo/dynamic-form-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ composer update acseo/dynamic-form-bundle
```

Composer will install the bundle to your project's `vendor/acseo` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new ACSEO\Bundle\DynamicFormBundle\ACSEODynamicFormBundle(),
    );
}
```

### Step 3: Controller example

``` php

public function indexAction()
{
    $formArray = array(
        'name' => array(
            'type' => 'text',
            'options' => array(
                'label' => 'Nom',
                'help' => 'Renseignez votre nom',
                'picto' => 'text',
            ),
            'constraints' => array(
                'NotBlank' => true,
                'Length' => array('min' => 2),
            )
        ),
        'amount' => array(
            'type' => 'money',
            'options' => array(
                'label' => 'Montant TTC',
                'help' => 'Montant total de votre achat',
                'picto' => 'money',
            ),
            'constraints' => array(
                'NotBlank' => true
            )
        ),
        'buydate' => array(
            'type' => 'date',
            'options' => array(
                'label' => "Date d'achat",
                'picto' => 'date',
            ),
            'constraints' => array(
                'NotBlank' => true
            )
        ),
        'product' => array(
            'type' => 'text',
            'options' => array(
                'label' => 'Libellé produit',
                'picto' => 'text',
                'help' => 'lorem ipsum',
                'data' => array(
                    array('value' => 'Foo'),
                    array('value' => 'Bar')
                )
            ),
            'constraints' => array(
                'NotBlank' => true
            ),
            'multiple' => true
        ),
    );

    $arrayFormProvider = $this->get('acseo.form.array.provider');
    $arrayFormProvider->setFormArray($formArray);

    $form =  $this->get('acseo.form.manager')->createForm($arrayFormProvider);

    if ($this->container->get('request')->isMethod('POST')) {
        $form->handleRequest($this->container->get('request'));

        if ($form->isValid()) {


            $data = $form->getData();
            // do anything you want ...
        }
    }


    return array('form' => $form->createView());
}

```

### Step 4: Create your own provider

You can create your own service form provider that implements FormProviderInterface.

### Step 5: Exemple of JS to implement in your project

```js


<script>

        function initializeEvents() {
            $(".tipped").tipper();
        }

        $(document).ready(function() {
            initializeEvents();
        });

        var collectionHolder = $('.prototype');

        var $addTagLink = $('<a href="#" class="add_tag_link">Ajouter</a>');
        var $newLinkLi = $('<div class="childfieldwrap"></div>').append($addTagLink);

        $(function() {

            //collectionHolder.append($newLinkLi);

            $addTagLink.on('click', function(e) {
                e.preventDefault();
                addTagForm(collectionHolder, false);
            });

            collectionHolder.find('div.fieldwrap .fieldwrap').each(function(index) {
                console.log(index);
                if(index) {
                    addTagFormDeleteLink($(this));
                }
            });


            if (!$('.prototype').children().length) {
                addTagForm(collectionHolder, true);
            }


            $('input:first', collectionHolder).after($addTagLink);
        });

        function addTagForm(collectionHolder, isFirst) {
            var prototype = collectionHolder.attr('data-prototype');

            var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

            var $newFormLi = $('<div class="childfieldwrap"></div>').append(newForm);

            collectionHolder.append($newFormLi);
            if(!isFirst)
                addTagFormDeleteLink($newFormLi);

            initializeEvents();
        }

        function addTagFormDeleteLink($tagFormLi) {
            var $removeFormA = $('<a href="#" class="delete">Supprimer</a>');
            $('input', $tagFormLi).after($removeFormA);

            $removeFormA.on('click', function(e) {
                e.preventDefault();
                $tagFormLi.remove();
            });
        }

    </script>

```