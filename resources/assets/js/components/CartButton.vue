<template>
    <div class="quantity-block row">
        <div class="col-6">
            <div class="quantity quantity-minus z-depth-1" @click="decrementQuantity">-</div>
            <input type="text" class='quantityInput' v-model="quantityInput" disabled>
            <div class="quantity quantity-plus z-depth-1" @click="incrementQuantity">+</div>
        </div>
        <div class="col-6"><a href="#" @click.prevent="addToCart" class="btn btn-sqaure btn-green z-depth-1-half">КУПИТЬ</a></div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            product: {
                type: String,
                required: true
            },
        },
        data() {
            return {
                quantityInput: 1,
            }
        },
        computed: {
            parsedProduct: function () {
                return JSON.parse(this.product);
            },
            ...mapState([
                'added',
            ])
        },
        methods: {
            incrementQuantity: function () {
                this.quantityInput = this.quantityInput >= this.parsedProduct.maxItems ? this.parsedProduct.maxItems : this.quantityInput += 1;
            },
            decrementQuantity: function () {
                if (this.quantityInput === 1) {
                    return;
                }
                this.quantityInput -= 1;
            },
            addToCart: function () {
                this.parsedProduct.quantity = this.quantityInput;

                this.$store.dispatch('addToCart', {
                    product: this.parsedProduct,
                });
            }
        }
    }

</script>
