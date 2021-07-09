<?php

use App\Core\Controller;
class Carros extends Controller

{

    public function index()
    {
        $carroModel = $this->Model("Carro");

        $carros = $carroModel->listAll();

        echo json_encode($carros, JSON_UNESCAPED_UNICODE);
    }

    public function store()

    {

        $novoCarro = $this->getRequestBody();

        $carroModel = $this->Model("Carro");

        $carroModel->nome = $novoCarro->nome;
        $carroModel->placa = $novoCarro->placa;
        $carroModel->idPreco = $carroModel->getPreco()->idPreco;

        $erros = $this->validarCampos();
        if (count($erros) > 0) {
            http_response_code(404);
            echo json_encode($erros, JSON_UNESCAPED_UNICODE);

            exit();
        }
        
        $carroModel = $carroModel->insert();

    
        if ($carroModel) {
            http_response_code(201);
            echo json_encode($carroModel, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir um novo carro"]);
        }
    }
    public function update($id)
    {
        $carroEditar = $this->getRequestBody();

        $carroModel = $this->Model("Carro");
        $carroModel = $carroModel->findById($id);

        if (!$carroModel) {
            http_response_code(404);
            echo json_encode(["erro" => "Carro não encontrada"]);
            exit();
        }

        $carroModel->nome = $carroEditar->nome;
        $carroModel->placa = $carroEditar->placa;

        $erros = $this->validarCampos();
        if (count($erros) > 0) {
            http_response_code(404);
            echo json_encode($erros, JSON_UNESCAPED_UNICODE);

            exit();
        }

        if ($carroModel->update()) {
            http_response_code(204);
    
        } else {
            http_response_code(500);
            echo json_encode(["erro " => "Problemas ao editar carro"]);
    
        }
    
    }

    public function delete($id)
    {
        $carroModel = $this->Model("Carro");
        $carroModel = $carroModel->findById($id);

        if (!$carroModel) {
            http_response_code(404);
            echo json_encode(["erro" => "Carro não encontrada"]);
            exit();
        }

        $valorPrimeiraHora = $carroModel -> getPreco() -> primeiraHora;
        $valorDemaisHoras = $carroModel -> getPreco() -> demaisHoras;

        $horaEntrada = floatval($carroModel -> getHourIn($carroModel -> horaEntrada)->hora);
        $carroModel -> horaSaida = $carroModel -> getNowHour() -> hora;
        $horaSaida = floatval($carroModel -> getHourIn($carroModel -> horaSaida) -> hora);

        $horasEstacionado = $horaEntrada - $horaSaida;
        if ($horasEstacionado < 0) {
            $horasEstacionado *= -1;
        }
        if ($horasEstacionado > 1) {
            $demaisHorasEstacionado = $horasEstacionado - 1;
            $carroModel -> valorPago = $demaisHorasEstacionado * floatval($valorDemaisHoras);
            $carroModel -> valorPago += floatval($valorPrimeiraHora);
        } else {
            $carroModel -> valorPago = floatval($valorPrimeiraHora);
        }

    
        if ($carroModel->delete()) {
            http_response_code(204);
        } else {
            http_response_code(500);
            echo json_encode(["erro " => "Problemas ao editar carro"]);
        }
    }

    private function validarCampos()
    {
        $carroModel = $this->Model("Carro");
        $erros = [];

        if (!isset($carroModel->nome) && $carroModel->nome == "") {
            $erros[] = "O nome é obrigatório";
        }

        if (!isset($carroModel->placa) && $carroModel->placa == "") {
            $erros[] = "O placa é obrigatório";
        }

        return $erros;
    }
}
