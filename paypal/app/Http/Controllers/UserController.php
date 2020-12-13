<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class UserController extends Controller
{
    //
    public function profile()
    {
    	$title = 'Meu Perfil';

    	return view('store.user.profile', compact('title'));
    }

    public function profileUpdate(Request $request, User $user)
    {
        //parâms 
        $this->validate($request, $user->rulesProfile);
        
        $user = $user->find(auth()->user()->id);
        $user->name = $request->name;
        $user->save();
        
        return redirect()
                ->route('user.profile')
                ->with('success', 'Perfil Atualizado com Sucesso!');
    }
    
    public function password()
    {
        $title = 'Atualizar Senha';
        
        return view('store.user.password', compact('title'));
    }
    
    public function passwordUpdate(Request $request, User $user)
    {
        //preciso ter a validação para q não dê erro
        $this->validate($request, $user->rulesPassword);
        //pego o id do usu logado
        $user = $user->find(auth()->user()->id);
        //para alterar: faço um bcrypt em cima da senha q a pesso passou
        $user->password = bcrypt($request->password);
        //salvando
        $user->save();
        //redirecionando para:
        return redirect()
                ->route('user.password')
                //passo a mensagem lá na view
                ->with('success', 'Senha Atualizada com Sucesso!');
    }
    //quando acesso esse método redireciona o usu para onde quiser no caso home, vou em view/store/template/header.blade.... e coloco a rota 
    public function logout()
    {
        Auth::logout();
        
        return redirect()->route('home');
    }
}
