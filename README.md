## Laravel - Followize

[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

- Envia as conversões para o RD Station

## Instalação

```bash
composer require agenciafmd/laravel-followize:dev-master
```

## Configuração

Para que a integração seja realizada, precisamos da **chave do cliente e a chave da equipe**

As duas chaves podem ser encontradas no menu Configurações do painel da Followize


Colocamos estas chaves no nosso .env

```dotenv
FOLLOWIZE_CLIENT_KEY=VYfa6Oo1oaCIeQ68Ase9dSOBPdgRvWtJ
FOLLOWIZE_TEAM_KEY=VFTGa6Oo1oaCIeQ68Ase9dSOBPdgPvRtJ
```

## Uso

Envie os campos no formato de array para o SendConversionsToFollowize.

**Campos obrigatórios**

**clientKey**	-    Chave do cliente

**teamKey**    -     Chave da equipe

**conversionGoal**   -	Identificador do ponto de conversão

**name**	-   Nome

**email**   -   E-mail

**phone**   -  	Telefone

**cellPhone**  -  Celular do cliente


**Observação**

Para os parâmetros email, phone e cellPhone

No envio do lead para o Followize, deverá conter pelo menos um desses três parâmetros. Se pelo menos um deles for enviado os outros dois serão opcionais

**Retorno do endpoint**

success	 - Erro retorna 0 ou Sucesso retorna 1. Indica se houve sucesso.

error  -  0 ou 1. Indica se houve erro.

leadId	-  ID do lead gerado.

leadType -  new ou treatment. Informa se o lead é novo ou se é uma interação de um lead existente.


**Erros**

4000 - Um ou mais campos obrigatórios não enviados.

4001 - Chave de cliente inválida.

4002 - Chave de equipe inválida.

4003 - Falha ao cadastrar o contato.

4004 - Nenhum atendente encontrado na equipe enviada.

4005 - Falha ao cadastrar a conversão.

4006 - Verificar bases legais.

Para que o processo funcione pelos **jobs**, é preciso passar os valores conforme mostrado abaixo.

```php
use Agenciafmd\Followize\Jobs\SendConversionsToFollowize;

$data['email'] = 'carlos@fmd.ag';
$data['nome'] = 'Carlos';

SendConversionsToFollowize::dispatch($data + [
        'conversionGoal' => 'contato',
    ])
    ->delay(5)
    ->onQueue('low');
```

Note que no nosso exemplo, enviamos o job para a fila **low**.

Certifique-se de estar rodando no seu queue:work esteja semelhante ao abaixo.

```shell
php artisan queue:work --tries=3 --delay=5 --timeout=60 --queue=high,default,low
```
