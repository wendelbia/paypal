<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use App\Models\Cart;
use PayPal\Api\PaymentExecution;

class PayPal extends Model
{
	private $apiContext;
	//guarda o valor de identificação única
    private $identify;
    private $cart;
    //passo um obj de cart como params isso é para conseguir pegar os itens do nosso carrinho
    public function __construct(Cart $cart)
    {
    	//recebe a chave q tem as informações de cliente e chave e para acessá-lo chamo o paypal.cliente_id... q é o arquivo de config/paypal
        $this->apiContext = new ApiContext(
        	//passa as credenciais para a integrração como paypal
             new OAuthTokenCredential(config('paypal.client_id'), config('paypal.secret_id'))
        );
        //pego o setting no arquivo paypal.php na config
        $this->apiContext->setConfig(config('paypal.settings'));
        //bcrypt para criar um valor único
        $this->identify = bcrypt(uniqid(date('YmdHis')));
        //verifico se está chegando com sucesso e vou na rota index para redirecionar
        //dd($cart); já está funcionando então posso recuperar os item como na função getItem(){...} e etc...
        $this->cart = $cart;
    }
/* O MÉTODO GENERATE TEM MUITA INFORMAÇÃO ENTÃO SERÁ QUEBRADO
    //gera uma nova compra no paypal, faz o envio
    public function generate()
    {
    //tipo de pay(pagamento)
		$payer = new Payer();
        $payer->setPaymentMethod("paypal");
        
        //produtods q está vendando
        //$item1 = new Item();
        //$item1->setName('Ground Coffee 40 oz')
        //BRL q é brasileiro
        //    ->setCurrency('BRL')->setQuantity(1)->setPrice(7.5);
        //$item2 = new Item();
        //$item2->setName('Granola bars')->setCurrency('BRL')           ->setQuantity(5)->setPrice(2);
          
            //para verificar se está recuperando os itens
         //dd($this->cart->getItems());

            //crio um array vazio
            $items = [];
         //como qro recuperar os itens faço um foreach()
            $itemsCart = $this->cart->getItems();
         foreach ($itemsCart as $itemCart) {
         	//esse Item() faz parte da classe do paypal
           	$item = new Item();
           	//pego o item especificando qual e em qual posição q é name
           	 $item->setName($itemCart['item']->name)
           	//coloco a moeda
           		->setCurrency('BRL')
           		//quantidade
           		->setQuantity($itemCart['qtd'])
           		//preço do produto
           		->setPrice($itemCart['item']->price);
           		//adiciona o looping q está no momento do foreach
           		array_push($items, $item);
           }  

        $itemList = new ItemList();
        $itemList->setItems($items);
        
        //detales do venda
        $details = new Details();
        /*se quiser trabalhar o preço do frete passa essa configuração tarifas, taxas de entrega subtotal, no momento usar apenas o total
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);
        //total geral da compra, aqui deve ser colocado tafifas, taxa de entrega sñ vai dar erro
            //chamo o método do Cart
        $details->setSubtotal($this->cart->total());
        $amount = new Amount();
        $amount->setCurrency("BRL")
            ->setTotal($this->cart->total())
            //detales
            ->setDetails($details);
        
        $transaction = new Transaction();
        $transaction->setAmount($amount)
       		//os itens da compra
            ->setItemList($itemList)
            //descrição
            ->setDescription("Uma descrição qualquer")
            //identificador q foi passado no __construct
            ->setInvoiceNumber($this->identify);
        //redirecionamento de url
        $baseRoute = route('return.paypal');
        $redirectUrls = new RedirectUrls();
        //faço a rota e depois o controller
        $redirectUrls->setReturnUrl("{$baseRoute}?success=true")
                    ->setCancelUrl("{$baseRoute}?success=false");
        
        
        $payment = new Payment();
        //order tenho opição de ver os itens que estão lá em cima
        $payment->setIntent("order")
        //payer é a configuração lá de cima
            ->setPayer($payer)
        //redirectUrls pegados do transaction a cima
            ->setRedirectUrls($redirectUrls)
            //transaction em array são os dado sda transação
            ->setTransactions(array($transaction));
        
        
        try {
        	//aqui é pedido para criar
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
            exit(1);
        }
        //me devolve o link
        $approvalUrl = $payment->getApprovalLink();
        //me retorna 
        return $approvalUrl;
    }
*/
    //tem como responsabilidade gerar o link de aprovação do paypal
    public function generate()
    {
        $payment = new Payment();
        $payment->setIntent("order")
        //aqui em vez de chamr a var payer chamo o método payer()
            ->setPayer($this->payer())
            ->setRedirectUrls($this->redirectsUrl())
            ->setTransactions([$this->transaction()]);
        
        try {
            $payment->create($this->apiContext);

            //quando gera o pedido no paypal eu salvo essa ordem

            //esse é o payment id, cada vez q gerar uma transação o paypal cria um token diferente a cada transação vl q nunca se repete
            //dd($paymentId);
            $paymentId = $payment->getId();

            $approvalUrl = $payment->getApprovalLink();
               
        	//não retorno mais a url retorno um array
        	//return $approvalUrl;
        	return [
                'status'        => true,
                'url_paypal'    => $approvalUrl,
                'identify'      => $this->identify,
                'payment_id'    => $paymentId,
            ];
            //após modificar vou no PayPalController paypal(){}

        } catch (Exception $ex) {
        	//vamos mudar o catch
            //exit(1);
            return [
            	//se status false então cai na mensagem q gerou a exception 
                'status'    => false,
                'message'   => $e->getMessage(),
            ];
        }
    }

    public function payer()
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        return $payer;
    }

    public function items()
    {
        /*
        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('BRL')
            ->setQuantity(1)
            ->setPrice(7.5);
        $item2 = new Item();
        $item2->setName('Granola bars')
            ->setCurrency('BRL')
            ->setQuantity(5)
            ->setPrice(2);
         * 
         */
        $items = [];
        $itemsCart = $this->cart->getItems();
        foreach( $itemsCart as $itemCart ) {
            $item = new Item();
            $item->setName($itemCart['item']->name)
                    ->setCurrency('BRL')
                    ->setQuantity($itemCart['qtd'])
                    ->setPrice($itemCart['item']->price);
            
            array_push($items, $item);
        }
        
        return $items;
    }

    public function itemsList()
    {
        $itemList = new ItemList();
        $itemList->setItems($this->items());
        
        return $itemList;
    }

    public function details()
    {
        $details = new Details();
        $details->setSubtotal($this->cart->total());
        /*
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);
         * 
         */
        
        return $details;
    }

    public function amount()
    {
        $amount = new Amount();
        $amount->setCurrency("BRL")
            ->setTotal($this->cart->total())
            ->setDetails($this->details());
        
        return $amount;
    }

    public function transaction()
    {
        $transaction = new Transaction();
        $transaction->setAmount($this->amount())
            ->setItemList($this->itemsList())
            ->setDescription("Compra Loja Laravel com PayPal")
            ->setInvoiceNumber($this->identify);
        
        return $transaction;
    }

    public function redirectsUrl()
    {
        $baseRoute = route('return.paypal');
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("{$baseRoute}?success=true")
                    ->setCancelUrl("{$baseRoute}?success=false");
                    
        return $redirectUrls;
    }
//recebe os vlores do PaypalController returnPaypal(){}
    public function execute($paymentId, $token, $payerID)
    {
    	//o payment recupera os dados do pagamento, passo algumas infomações e o apiContext q tem os dados da conexão com paypal
        $payment = Payment::get($paymentId, $this->apiContext);
        //vamos dar um dd para ver oq ele retornar e observar o tanto q ele é útil
        //dd($payment);
        //ele traz muita informação como state: created q é o status de criado, id de identificação transação, dados da quantidade, vendedor, quem comprou, email de quem comprou e etc...

        //se quiser pegar os dados do usu por exemplo:
        //dd($payment->payer->payer_info->shipping_address->city);
        //no paypal é o status de creado preciso aprovar esse pedido 
        //dd($payment->getState());
        //verifico se o status é diferente de aprovado eu entro na condição e aprovo o pedido do paypal
        if( $payment->getState() != 'approved' ) {
        	//chamo a classe do paypal 
            $execution = new PaymentExecution();
            //chamo o método para executar o payeID 
            $execution->setPayerId($payerID);
            //result armazena esse resultado como params temos a var q recebe a classe e o contexto q autentica o paypal
            $result = $payment->execute($execution, $this->apiContext);
            //dd($result->getState()); será status de aprovado
            return $result->getState();

        }
        
        return $payment->getState();
    }
}
