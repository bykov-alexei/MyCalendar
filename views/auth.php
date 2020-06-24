<?php include 'views/partials/header.php'; ?>

<div id='auth'>
    <p>{{ message }}</p>
    <form action='/users.php'>
        <div><p>Логин:</p></div>
        <div><input name='login' v-model:value='user.login'></div>
        <div><p>Пароль:</p></div>
        <div><input name='password' type='password' v-model:value='user.password'></div>
        <div><input type='button' @click='signIn' value='Войти'></div>
        <div><input type='button' @click='signUp' value='Зарегестрироваться'></div>
    </form>
</div>

<script>
let auth = new Vue({
    el: '#auth',
    data: {
        message: '',
        user: {
            login: '',
            password: '',
        }
    },
    methods: {
        signIn: async function(event) {
            let form = event.target.closest('form');
            let data = new FormData(form);
            let response = await fetch('/users.php', {method: 'post', body: data});
            let text = await response.text();
            console.log(response.status);
            console.log(text);
            if (response.status == 202) {
                window.location.replace('/tasks.php?list');
            } else {
                let json = JSON.parse(text);
                this.message = json['message'];
            }
        },
        signUp: async function(event) {
            let response = await fetch('/users.php', {method: 'put', body: JSON.stringify(this.user), headers: {'Content-Type': 'application/json'}});
            let text = await response.text();
            console.log(response.status);
            console.log(text);
            if (response.status == 201) {
                window.location.href='/tasks.php?list';
            } else {
                let json = JSON.parse(text);
                this.message = json['message'];
            }
        },
        auth: function(token) {
            windows.location.replace('/index.php');
        }
    }
})
</script>

<?php include 'views/partials/footer.php'; ?>