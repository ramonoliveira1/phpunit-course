<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

$leilao = new Leilao('Fiat Uno');
$maria = new Usuario('Maria');
$joao = new Usuario('João');
$cleber = new Usuario('Cleber');
$paulo = new Usuario('Paulo');

$leilao->recebeLance(new Lance($maria, 5000));
$leilao->recebeLance(new Lance($joao, 7000));
$leilao->recebeLance(new Lance($cleber, 9000));
$leilao->recebeLance(new Lance($paulo, 15000));

$avaliador = new Avaliador();
$avaliador->avalia($leilao);

var_dump($avaliador->getMaioresLances());