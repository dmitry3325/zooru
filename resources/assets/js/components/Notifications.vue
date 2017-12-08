<template>
    <div class="notification-container">
        <transition-group name="bounce">
            <div class="notification z-depth-1-half" v-for="(notification, index) in list" :key="index">
                <img v-if="index === 0" :src="imgUrl" class="alert-img">
                <a class="close" @click="remove(notification.index)">
                    <i class="material-icons">&#xE5CD;</i>
                </a>
                <div class="notification-header">{{ notification.header }}</div>
                <span>{{ notification.body }}</span>
            </div>
        </transition-group>
    </div>
</template>
<script>
    module.exports = {
        name: 'notification',
        data: function () {
            return {
                list: [],
                index: 0
            }
        },
        computed: {
            imgUrl: function () {
                return '/images/alert' + this.randomInt(1, 3) + '.png';
            }
        },
        mounted: function () {
            let self = this;
            Vue.Events.on('alertMessage', function (body, duration = 2, header = null) {
                self.add(body, duration, header);
            });

            Vue.Events.emit('alertMessage', ['Добро пожаловать в наш прекрасный магаз! Купи корма и игрушек своему зверю :)', 15, 'Алоха!']);
        },
        methods: {
            add: function (body, duration, header) {
                let index = ++this.index;
                let notification = {
                    header: header,
                    body: body,
                    index: index,
                };

                //защита от дублирования
                if(this.list.length === this.list.filter(notification => notification.body === body).length){
                    return true;
                }

                this.list = [notification, ...this.list];

                let self = this;
                setTimeout(function () {
                    self.remove(index);
                }, duration * 1000);
            },
            remove: function (index) {
                this.list = this.list.filter(notification => notification.index !== index);
            },
            randomInt: function (min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

        }
    };
</script>

<style lang="scss" scoped>
    .notification-container {
        position: fixed;
        z-index: 100;
        bottom: -1px;
        right: 5%;
        max-width: 20%;

        .notification {
            position: relative;
            background: #fff;
            padding: 12px;
            min-width: 150px;
            border-radius: 2px;
            border: 1px solid #CBCBD0;
            margin-bottom: 15px;
        }

        .alert-img {
            max-height: 100px;
            position: absolute;
            top: -91px;
        }

        .notification-header {
            font-size: 1em;
        }

        .close {
            position: absolute;
            right: 1em;
            top: 1em;
            color: #CBCBD0;
            i.material-icons {
                font-size: 1.3em;
            }
        }

        span {
            font-size: 0.9em;
        }

        .bounce-enter-active {
            animation: bounce-in .5s;
        }
        .bounce-leave-active {
            animation: bounce-in .5s reverse;
        }
        @keyframes bounce-in {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.5);
            }
            100% {
                transform: scale(1);
            }
        }
    }
</style>