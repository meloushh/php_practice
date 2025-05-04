@extends('/jinsei/frontend/layout.php')

@section_start('body')
<div class="flex justify_center align_center" style="height: 100vh">
    <div class="border1 p4 w100" style="max-width: 600px">
        <p class="fs4 bold text-center">Login</p>

        <form action="/login" method="POST" style="display: grid; 
            grid-template-columns: max-content auto;
            row-gap: var(--size2);
            justify-items: right;" 
            class="mt2">

            <label class="p2 pl0">Email*</label>
            <input type="text" name="email" class="border1 block p2 w100">
            <label class="p2 pl0">Password*</label>
            <input type="password" name="password" class="border1 block p2 w100">
            <div>&nbsp;</div>
            <input type="submit" value="Login" class="btn1">
        </form>

        <p>
            <a href="/register">Register</a>
        </p>
    </div>
</div>
@section_end