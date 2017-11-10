<template>
    <div class="cart-window">
        <div class="back-wrap" v-if="showPopUp" @click="showPopUp = false"></div>
        <a class="header_up_item" @click="showPopUp = true">
            <i class="material-icons">&#xE8CC;</i>
            <span class="cart-window__count">{{totalCount}}</span>
            <span v-if="!totalPrice">Корзина</span>
            <span v-else>{{totalPrice}}&#8381;</span>
        </a>
        <div v-show="showPopUp" id="cart-popup" class="cart-popup z-depth-1-half">
            <div class="cart-popup__inside">
                <div v-if="totalCount > 0">
                    <span class="cart-window__title">Ваша корзина</span>
                    <hr/>
                    <ul>
                        <li v-for="product in cart" class="cart-window__item">
                            <div class="row">
                                <div class="col-2"><img :src="product.photo" class="cart-window__item-img"></div>

                                <div class="col-6">
                                    <span class="cart-window__item-text">{{ product.title }}</span>
                                </div>
                                <div class="col-3">
                                    <span class="cart-window__item-text">{{ product.quantity
                                        }}шт. <br/>{{ product.price * product.quantity }}&#8381;</span>
                                </div>

                                <div class="col-1"><span @click="removeFromCart(product.id)"
                                                         class="cart-window__remove_btn"><i class="material-icons">&#xE5CD;</i></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="cart-window__total_price right">Итого, без стоимости доставки: <span class="cart-window__total_price_num">{{ totalPrice }}&#8381;</span></div>
                    <a href="#" class="btn btn-sqaure btn-trans"
                       @click.prevent="showPopUp = false;">ПРОДОЛЖИТЬ ПОКУПКИ</a>
                    <a href="/cart" class="btn btn-sqaure btn-green fl_r">ОФОРМИТЬ ЗАКАЗ</a>
                </div>
                <div v-else class="cart-window__title">Ваша корзина пуста</div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapGetters} from 'vuex'

    export default {
        data: function () {
            return {
                showPopUp: true
            }
        },
        computed: {
            ...mapState([
                'cart',
            ]),
            ...mapGetters([
                'totalPrice',
                'totalCount'
            ])
        },
        methods: {
            removeFromCart: function (id) {
                this.$store.dispatch('removeFromCart', {
                    id: id
                });
            }
        }
    }

</script>