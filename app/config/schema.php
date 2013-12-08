<?php

$schema = array(
    '\\Blog\\Entity\\Post' => array(
        'table' => 'posts',
        'columns' => array(
            'id' => 'id',
            'title' => 'title',
            'content' => 'content'
        ),
        'relations' => array(
            'comments' => array(
                'relation' => 'one-to-many',
                'targetEntity' => '\\Blog\\Entity\\Comment',
                'mappedBy' => 'post',
            ),
        ),
    ),
    '\\Blog\\Entity\\Comment' => array(
        'table' => 'comments',
        'columns' => array(
            'id' => 'id',
            'content' => 'content',
        ),
        'relations' => array(
            'post' => array(
                'relation' => 'many-to-one',
                'targetEntity' => '\\Blog\\Entity\\Post',
                'join_column_name' => 'post_id',
                'referenced_column_name' => 'id',
            ),
            'user' => array(
                'relation' => 'many-to-one',
                'targetEntity' => '\\Blog\\Entity\\User',
                'join_column_name' => 'user_id',
                'referenced_column_name' => 'id',
            ),
        ),
    ),
    '\\Blog\\Entity\\User' => array(
        'table' => 'users',
        'columns' => array(
            'id' => 'id',
            'name' => 'name',
            'email' => 'email'
        ),
        'relations' => array(
            'comments' => array(
                'relation' => 'one-to-many',
                'targetEntity' => '\\Blog\\Entity\\Comment',
                'mappedBy' => 'user',
            ),
        ),
    ),
);
return $schema;