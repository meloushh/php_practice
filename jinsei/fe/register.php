@extends('/jinsei/fe/web_template.php')

@section_start('body')
<div class="flex justify_center align_center" style="height: 100vh">
    <div class="border1 p4 w100" style="max-width: 600px">
        <p class="fs4 bold text-center">Registration</p>

        <form action="/register" method="POST" style="display: grid; 
            grid-template-columns: max-content auto;
            row-gap: var(--size2);
            justify-items: left;" 
            class="mt2">

            <label class="p2 pl0">Email*</label>
            <input type="text" name="email" class="border1 block p2 w100">
            <label class="p2 pl0">Password*</label>
            <input type="password" name="password" class="border1 block p2 w100">
            <label class="p2 pl0">Repeat password*</label>
            <input type="password" name="repeat_password" class="border1 block p2 w100">
            <input type="submit" value="Register" class="btn1 p1" style="grid-column-start: span 2; justify-self: center">
        </form>

        <p class="mt3 text-center">
            <a href="/" class="a">Back to login</a>
        </p>
    </div>
</div>
@section_end