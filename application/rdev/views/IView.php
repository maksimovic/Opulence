<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Defines the interface for views to implement
 */
namespace RDev\Views;

interface IView
{
    /**
     * Renders the output of the view
     *
     * @return string The output (eg HTML, text, etc)
     */
    public function render();
} 