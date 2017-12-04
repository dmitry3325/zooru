<template>
    <div class="login-window">
        <div class="back-wrap darken" v-if="showPopUp" @click="showPopUp = false"></div>
        <a class="header_up_item" @click="showPopUp = true">
            <a class="header_up_item"><i class="material-icons">&#xE853;</i>
                <span>Вход / Регистрация</span>
            </a>
        </a>
        <div v-show="showPopUp" class="login-popup row justify-content-center"
             :class="[registrationMode ? 'col-6' : 'col-4']">
            <div v-if="registrationMode" class="col-6 left-panel">
                <div class="reasons">
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
                        <input type="text" class="half-width fl_l" placeholder="Имя"/>
                        <input type="text" class="half-width fl_r" placeholder="Фамилия"/>
                        <input type="text" placeholder="email"/>
                        <a href="#" class="btn btn-sqaure btn-dark">Регистрация</a>
                        <p class="or"><span>или</span></p>
                        <h2>FACEBOOK</h2>
                        <div class="rules-text">Нажимая кнопку «Регистрация», Вы принимаете <a href="#">условия использования</a></div>
                        <div v-if="!registrationMode">Впервые здесь? <a class="blue" @click="registrationMode = true">Зарегистрируйтесь</a></div>
                        <div v-if="registrationMode">Уже зарегистрировались? <a class="blue" @click="registrationMode = false">Войдите</a>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <h4>Вход</h4>
                    <div class="reg-form">
                        <input type="text" placeholder="email"/>
                        <input type="password" placeholder="password"/>
                        <a href="#" class="btn btn-sqaure btn-dark">Вход</a>
                        <p class="or"><span>или</span></p>
                        <div>facebook</div>
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
                showPopUp: true,
                registrationMode: true,
            }
        },
        computed: {
            ...mapState([
                'auth',
            ])
        },
        methods: {}
    }

</script>