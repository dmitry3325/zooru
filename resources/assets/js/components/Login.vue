<template>
    <div class="login-window">
        <div class="back-wrap darken" v-if="showPopUp" @click="showPopUp = false"></div>
        <a class="header_up_item"><span @click="windowMode ='login'; showPopUp = true;">Вход</span></a>
        <a class="header_up_item seporator"><span> / </span></a>
        <a class="header_up_item"><span @click="windowMode = 'reg'; showPopUp = true;">Регистрация</span></a>

        <div v-show="showPopUp" class="login-popup row justify-content-center"
             :class="[windowMode==='reg' ? 'col-6' : 'col-4']">
            <div v-if="windowMode==='reg'" class="col-6 left-panel">
                <div class="reasons noselect">
                    <h4>Присоединяйтесь<br/>к нам!</h4>
                    <ul class="checklist">
                        <li><i class="material-icons">&#xE876;</i><span>Накопительная бонусная программа</span></li>
                        <li><i class="material-icons">&#xE876;</i><span>Дополнительные скидки</span></li>
                    </ul>
                </div>
            </div>
            <div class="right-panel" :class="[windowMode==='reg' ? 'col-6' : 'col-12']">
                <a class="close" @click="showPopUp = false">
                    <i class="material-icons">&#xE5CD;</i>
                </a>

                <!--registaration-->
                <div v-if="windowMode === 'reg'">
                    <h4>Регистрация</h4>
                    <div class="reg-form">
                        <input type="text" class="half-width fl_l" placeholder="Имя" v-model="regFirstName"
                               @keydown="delete regErrors.firstname"
                               :class="[regErrors && regErrors.hasOwnProperty('firstname') ? 'error' : '']">
                        <input type="text" class="half-width fl_r" placeholder="Фамилия" v-model="regLastName"
                               @keydown="delete regErrors.lastname"
                               :class="[regErrors && regErrors.hasOwnProperty('lastname') ? 'error' : '']">
                        <input type="text" placeholder="email" v-model="regEmail" @keydown="delete regErrors.email"
                               :class="[regErrors && regErrors.hasOwnProperty('email') ? 'error' : '']">
                        <input type="password" placeholder="пароль" v-model="regPassword"
                               @keydown="delete regErrors.password"
                               :class="[regErrors && regErrors.hasOwnProperty('password') ? 'error' : '']">

                        <div class="error" v-for="error in regErrors">{{ error[0] }}</div>
                        <a @click.prevent="registartion" class="btn btn-sqaure"
                           :class="[this.regBtnDisabled ? 'btn-disabled' : 'btn-green']">Регистрация</a>
                        <div class="rules-text">Нажимая кнопку «Регистрация», Вы принимаете <a href="#" class="blue"
                                                                                               target="_blank">условия использования</a>
                        </div>
                        <span v-if="loading" class="loading"></span>
                    </div>
                </div>

                <!--LOGIN-->
                <div v-else-if="windowMode === 'login'">
                    <h4>Вход</h4>
                    <div class="reg-form">
                        <input type="text" placeholder="email" v-model="email" @keydown="delete loginErrors.email"
                               :class="[loginErrors && loginErrors.hasOwnProperty('email') ? 'error' : '']">
                        <input type="password" placeholder="пароль" v-model="password"
                               @keydown="delete loginErrors.password"
                               @keydown.enter="login"
                               :class="[loginErrors && loginErrors.hasOwnProperty('password') ? 'error' : '']">
                        <div class="error" v-for="error in loginErrors">{{ error[0] }}</div>
                        <span v-if="loading" class="loading"></span>

                        <div class="remember left">
                            <label class="cbox">
                                Запомни меня
                                <input type="checkbox" v-model="rememberCheckbox">
                                <span class="checkmark"></span>
                            </label>
                            <a @click="windowMode = 'remember'" class="blue fl_r">Забыли пароль?</a>
                        </div>

                        <a @click.prevent="login" class="btn btn-sqaure btn-dark" href="/login">Вход</a>
                    </div>
                </div>

                <!--password remember-->
                <div v-else-if="windowMode === 'remember'">
                    <h4>Напомнить пароль</h4>
                    <div class="reg-form">
                        <input type="text" placeholder="email" v-model="email">
                        <span v-if="loading" class="loading"></span>
                        <div class="error" v-text="forgottenError"></div>
                        <a @click.prevent="rememberPassrord" class="btn btn-sqaure btn-green">Получить новый пароль</a>
                    </div>
                </div>

                <!--bottom-->
                <p class="or"><span>или</span></p>
                <div v-if="windowMode !== 'reg' ">Впервые здесь? <a class="blue" @click="windowMode = 'reg'">Зарегистрируйтесь</a>
                </div>
                <div v-if="windowMode !== 'login'">Уже зарегистрировались? <a class="blue"
                                                                              @click="windowMode = 'login'">Войдите</a>
                </div>
                <!--bottom end-->
            </div>
        </div>

    </div>
</template>

<script>
    import {mapState, mapGetters} from 'vuex'

    export default {
        data: function () {
            return {
                regEmail: null,
                regPassword: null,
                regFirstName: null,
                regLastName: null,
                regErrors: {},

                email: null,
                password: null,
                rememberCheckbox: true,
                loginErrors: {},

                forgottenEmail: null,
                forgottenError: null,

                showPopUp: true,
                windowMode: 'login',
                loading: false,
            }
        },
        computed: {
            ...mapState([
                'auth',
            ]),
            regBtnDisabled: function () {
                return !this.regEmail || !this.regLastName || !this.regFirstName || !this.regPassword;
            }
        },
        methods: {
            registartion: function () {
                let self = this;
                self.loading = true;

                self.regErrors = {};

                Vue.axios.post('/registration', {
                    firstname: self.regFirstName,
                    lastname: self.regLastName,
                    email: self.regEmail,
                    password: self.regPassword,
                })
                    .then(function (response) {
                        self.loading = false;
                    })
                    .catch(function (error) {
                        self.regErrors = error.response.data.errors;
                        self.loading = false;
                    });
            },
            login: function () {
                let self = this;

                self.loading = true;
                self.loginErrors = {};

                Vue.axios.get('/login', {
                    params: {
                        password: self.password,
                        email: self.email,
                        remember: self.rememberCheckbox,
                    }
                })
                    .then(function (response) {
                        if (response.status === 200 && response.data.result) {
                            window.location.reload();
                        }
                        self.loading = false;
                    })
                    .catch(function (error) {
                        self.loginErrors = error.response.data.errors;
                        self.loading = false;
                    });
            },
            rememberPassrord: function () {
                let self = this;

                self.loading = true;
                self.forgottenError = null;

                Vue.axios.get('/recovery', {
                    params: {
                        email: self.email,
                    }
                })
                    .then(function (response) {
                        if (response.status === 200) {

                            self.forgottenError = response.data.message;

                            if(response.data.code === 200){
                                setTimeout(function () {
                                    self.windowMode = 'login';
                                }, 5000);
                            }
                        }

                        self.loading = false;
                    })
            },
        }
    }

</script>