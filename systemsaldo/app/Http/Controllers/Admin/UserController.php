<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UpdateFrofileFormRequest;

class UserController extends Controller
{
    public function profile()
    {
    	#38 para cria o template do site vou no bootstrap e pego o link do css
    	return view('site.profile.profile');
    }
    #42 mudo o params para validação
    #42 public function profileUpdate(Request $request) e incluo o alert na profile.blade que já está pronto em admin/includes/alets.blade.php
    public function profileUpdate(UpdateFrofileFormRequest $request)
    {
    	#40 para o upload de imagem pego os dados do usu logado
        //pode pegar esse dado pelo id ocultando no form mas é perigo pois por ser pego esse id e facilitar a invasão de perfil de outros usuários usando o auth() é mais seguro
    	$user = auth()->user();

    	#38 verifico
    	#dd($request->all());
    	#39 pego os dados do form e não deixo obrigatório editar a senha e imagem
    	$data = $request->all();
    	#39 se houver troca de senha vou criptografá-la
    	if ($data['password'] != null)
			$data['password'] = bcrypt($data['password']);
    	#39 para q ñ dê erro por que o password não pode entrar null eu destruo#
    	#40 agora faço o upload da imagem, vou em confg/filesystems.php
    	else
    		unset($data['password']);

    	#40 verifico se a pessoa tem uma imagem informada, pois quando entrar lá embaixo no update não irá atualizar a imagem
    	$data['image'] = $user->image;

    	#40 se tem imagem  e é imagem válida então
    	if ($request->hasFile('image') && $request->file('image')->isValid()) {

    		#40 se existe 
    		if ($user->image) 
    			#40 recebe o nome do usu, evitando imagem duplicada
    			$name = $user->image;
    		else
    			#40 posso tirar os caracteris especiais usando o kebab_case
    			$name = $user->id.kebab_case($user->name);
				$extenstion = $request->image->extension();
				$nameFile = "{$name}.{$extenstion}";
    		#40dd($nameFile);

    		#40 quando entrar aqui em upload ele ñ vai atualizar o upload da imagem por isso precisamos
    		$data['image'] = $nameFile;

    		#40 próxima etapa é fazer o upload, envio a imagem para dentro da pasta user em storage, se ñ houver o laravel cria. 
    		$upload = $request->image->storeAs('users', $nameFile);

    		#40 se ñ der certo faz o redirect back
    		if (!$upload)
    			return redirect()
    						->back()
    						->with('error', 'Falha ao fazer o upload da imagem!');

    	}
    	#39 Não é bom usar no formulário o id hidden escondido pois pode pegar o id de outras pessoas e auterar, pra isso pegasos uso auth
    	#39 $update = auth()->user()->update($data);

    	#40 mudo para e vamos no profile.blade para fazer uma melhoria que é exibir a imagem
    	$update = $user->update($data);
		
		if ($update)    	   
			return redirect()
						->route('profile')
						->with('success', 'Sucesso ao atualizar');
		return redirect()
				->back()
				->with('error', 'Falha ao atualizar o perfil...');
    }
}
