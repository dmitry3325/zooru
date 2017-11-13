<template>
    <div class="notification-list">
        <div v-for="(notification,index) in list"
             :data-index="index"
             class="notification animated bounceIn alert"
             :class="((notification.type)?'alert-'+notification.type:'alert-info')"
             @click="remove(index)">
            <div class="content">
                <div class="body">{{notification.body}}</div>
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        name: 'AppNotifications',
        data: function () {
            return {
                'list': {},
                'index': 0,
            }
        },
        beforeCreate: function () {
            this.$options.el = this.setTarget('body');
        },
        methods: {
            add: function (notification) {
                if (typeof notification.lifetime === 'undefined') {
                    notification.lifetime = 5000;
                }

                let index = ++this.index;
                this.$set(this.list, index, notification);

                let self = this;
                if (notification.lifetime) {
                    setTimeout(function () {
                        self.remove(index);
                    }, notification.lifetime);
                }

                return index;
            },
            remove: function (id) {
                let $n = this.$el.querySelector('.notification[data-index="' + id + '"]');
                if ($n) {
                    $n.classList.remove('bounceIn');
                    $n.classList.add('bounceOut');
                }
                let self = this;
                setTimeout(function () {
                    self.$delete(self.list, id);
                }, 500);
            }

        }
    };
</script>
<style>
    .notification-list {
        position: fixed;
        z-index: 100;
        bottom: 0;
        right: 1rem;
    }

    .notification-list .notification {
        position: relative;
        cursor: pointer;
        width: 400px;
    }

    .notification-list .notification .glyphicon-remove {
        font-size: 12px;
        position: absolute;
        top: 5px;
        right: 5px;
    }
</style>