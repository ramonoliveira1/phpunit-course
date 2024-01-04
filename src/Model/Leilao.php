<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    private string $descricao;

    private bool $finalizado = false;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->verificaUltimoLance($lance)) {
            throw new \DomainException('Usuário não pode propor 2 lances consecutivos');
        }

        if ($this->verificaLancePorUsuario($lance)) {
            throw new \DomainException('Usuário não pode propor mais de 5 lances por leilão');
        }

        $this->lances[] = $lance;
    }

    private function verificaUltimoLance(Lance $lance): bool
    {
        $ultimoLance = $this->getLances()[array_key_last($this->getLances())];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    private function verificaLancePorUsuario(Lance $lance): bool
    {
        $LancesPorUsuario = array_filter(
            $this->lances,
            function (Lance $lanceExistente) use ($lance) {
                return $lanceExistente->getUsuario() == $lance->getUsuario();
            }
        );
        return count($LancesPorUsuario) >= 5;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
}
