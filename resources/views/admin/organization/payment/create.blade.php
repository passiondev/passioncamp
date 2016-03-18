@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Payment</h1>
        </header>

        <component is="admin-organization-payment-create" inline-template>
            {{ Form::open(['route' => ['admin.organization.payment.store', $organization]]) }}

                <div class="form-group">
                    {{ Form::label('type', 'Type', ['class' => 'control-label']) }}
                    {{ Form::select('type', ['credit' => 'Credit', 'check' => 'Check'], null, ['id' => 'type', 'class' => 'form-control', 'v-model' => 'type']) }}
                </div>

                <div id="credit_fields" v-show="type == 'credit'">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="cc_number" class="control-label">Card Number</label>
                            <input type="text" id="cc_number" class="form-control js-form-input-card-number" data-stripe="number" required style="width:auto;max-width:100%" size="40">
                        </div>

                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="cc_exp_month" class="control-label">Expiration</label>
                                <input type="text" id="cc_expiry" class="form-control js-form-input-card-expiry" placeholder="mm / yy" data-stripe="exp" required>
                            </div>
                            <div class="form-group">
                                <label for="cc_cvc" class="control-label">CVC</label>
                                <input id="cc_cvc" type="text" size="8" data-stripe="cvc" class="form-control js-form-input-card-cvc">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="check_fields" v-show="type == 'check'">
                    <div class="form-group">
                        {{ Form::label('identifier', 'Check Number', ['class' => 'control-label']) }}
                        {{ Form::text('identifier', null, ['id' => 'identifier', 'class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('amount', 'Amount', ['class' => 'control-label']) }}
                    {{ Form::text('amount', $organization->balance, ['id' => 'amount', 'class' => 'form-control']) }}
                </div>

                <div class="form-group form-actions">
                    <button class="btn btn-primary">Submit</button>
                </div>

            {{ Form::close() }}
        </component>
    </div>
@stop

@section('foot')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.17/vue.min.js"></script>
    <script>
        Vue.component('admin-organization-payment-create', {
            data: function () {
                return {
                    type: 'credit'
                };
            },
            methods: function () {

            }
        });
        vue = new Vue({
            el: 'body'
        });
    </script>

@stop