<template>
    <div class="login-window">
        <div class="back-wrap darken" v-if="showPopUp" @click="showPopUp = false"></div>
        <a class="header_up_item" @click="showPopUp = true">
            <a class="header_up_item"><i class="material-icons">&#xE853;</i>
                <span>Вход / Регистрация</span>
            </a>
        </a>
        <div v-show="showPopUp" class="login-popup row justify-content-center" :class="[registrationMode ? 'col-6' : 'col-4']">
            <div v-if="registrationMode" class="col-6 left-panel">
                <div class="reasons noselect">
                    <h4>Присоединяйтесь<br/>к нам!</h4>
                    <ul class="checklist">
                        <li><i class="material-icons">&#xE876;</i><span>Накопительная бонусная программа</span></li>
                        <li><i class="material-icons">&#xE876;</i><span>Дополнительные скидки</span></li>
                    </ul>
                </div>
            </div>
            <div class="right-panel" :class="[registrationMode ? 'col-6' : 'col-12']">
                <a class="close" @click="showPopUp = false">
                    <i class="material-icons">&#xE5CD;</i>
                </a>
                <div v-if="registrationMode">
                    <h4>Регистрация</h4>
                    <div class="reg-form">
                        <input type="text" class="half-width fl_l" placeholder="Имя" v-model="regFirstName"
                               :class="[regErrors && regErrors.hasOwnProperty('firstname') ? 'error' : '']">
                        <input type="text" class="half-width fl_r" placeholder="Фамилия" v-model="regLastName"
                               :class="[regErrors && regErrors.hasOwnProperty('lastname') ? 'error' : '']">
                        <input type="text" placeholder="email" v-model="regEmail"
                               :class="[regErrors && regErrors.hasOwnProperty('email') ? 'error' : '']">
                        <div class="error" v-for="error in regErrors">{{ error[0] }}</div>
                        <a @click.prevent="registartion" class="btn btn-sqaure"
                           :class="[this.regBtnDisabled ? 'btn-disabled' : 'btn-green']">Регистрация</a>
                        <div class="rules-text">Нажимая кнопку «Регистрация», Вы принимаете <a href="#" class="blue" target="_blank">условия использования</a></div>
                        <p class="or"><span>или</span></p>
                        <div v-if="!registrationMode">Впервые здесь? <a class="blue" @click="registrationMode = true">Зарегистрируйтесь</a>
                        </div>
                        <div v-if="registrationMode">Уже зарегистрировались? <a class="blue" @click="registrationMode = false">Войдите</a>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <h4>Вход</h4>
                    <div class="reg-form">
                        <input type="text" placeholder="email" v-model="email" :class="[loginErrors && loginErrors.hasOwnProperty('email') ? 'error' : '']">
                        <input type="password" placeholder="пароль" v-model="password" :class="[loginErrors && loginErrors.hasOwnProperty('password') ? 'error' : '']">
                        <div class="error" v-for="error in loginErrors">{{ error[0] }}</div>
                        <a @click.prevent="login" class="btn btn-sqaure btn-dark" href="/login">Вход</a>
                        <p class="or"><span>или</span></p>
                        <div v-if="!registrationMode">Впервые здесь? <a class="blue" @click="registrationMode = true">Зарегистрируйтесь</a>
                        </div>
                        <div v-if="registrationMode">Уже зарегистрировались? <a class="blue" @click="registrationMode = false">Войдите</a>
                        </div>
                    </div>
                </div>

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
                regFirstName: null,
                regLastName: null,
                regErrors: {},

                email: null,
                password: null,
                loginErrors: {},

                showPopUp: true,
                registrationMode: false,
            }
        },
        computed: {
            ...mapState([
                'auth',
            ]),
            regBtnDisabled: function () {
                return !this.regEmail || !this.regLastName || !this.regFirstName;
            }
        },
        methods: {
            registartion: function () {
                let self = this;

                self.regErrors = {};

                Vue.axios.post('/registration', {
                    firstname: self.regFirstName,
                    lastname: self.regLastName,
                    email: self.regEmail,
                })
                    .then(function (response) {
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        self.regErrors = error.response.data.errors;
                    });
            },
            login: function () {
                let self = this;

                self.loginErrors = {};

                Vue.axios.get('/login', {
                    params: {
                        password: self.password,
                        email: self.email,
                    }
                })
                    .then(function (response) {
                        if (response.status === 200 && response.data.result) {
                            window.location.reload();
                        }
                    })
                    .catch(function (error) {
                        self.loginErrors = error.response.data.errors;
                    });
            },
        }
    }

</script>