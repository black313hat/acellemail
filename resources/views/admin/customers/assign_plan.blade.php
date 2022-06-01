<div class="iframe-modal-header">
    <div class="title">{{ trans('messages.subscription.change_plan') }}</div>
    <div class="close" onclick="parent.GlobalIframeModal.hide()"><i class="lnr lnr-cross"></i></div>
</div>

<div class="iframe-modal-body">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="sub-section">
            
                <h2>{{ trans('messages.subscription.select_a_plan') }}</h2>
                <p>{{ trans('messages.subscription.change_plan.select_below') }}</p>
            
                @if (empty($plans))
                    <div class="row">
                        <div class="col-md-6">
                            @include('elements._notification', [
                                'level' => 'danger',
                                'message' => trans('messages.plan.no_available_plan')
                            ])
                        </div>
                    </div>
                @else
                    <div class="price-box price-selectable">
                        <div class="price-table">
                    
                            @foreach ($plans as $plan)
                                <div class="price-line {{ is_object($customer->subscription) && !$customer->subscription->isEnded() && $customer->subscription->plan->uid == $plan->uid ? 'current' : '' }}">
                                    <div class="price-header">
                                        <lable class="plan-title">{{ $plan->name }}</lable>
                                        <p>{{ $plan->description }}</p>
                                    </div>
                                    
                                    <div class="price-item text-center">
                                        <div>{{ trans('messages.plan.starting_at') }}</div>
                                        <div class="plan-price">
                                            {{ \Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}
                                        </div>
                                        <div>{{ $plan->displayFrequencyTime() }}</div>
                                        
                                        @if (is_object($customer->subscription) && !$customer->subscription->isEnded() && $customer->subscription->plan->uid == $plan->uid)
                                            <a
                                                href="javascript:;"
                                                class="btn btn-mc_default mt-30" disabled>
                                                    {{ trans('messages.plan.current_subscribed') }}
                                            </a>
                                        @else
                                            <div class="mt-4 submit-box">
                                                <a
                                                    data-confirm="{{ trans('messages.customer.assign_plan.confirm', [
                                                        'plan' => $plan->name,
                                                        'customer' => $customer->uid,
                                                    ]) }}"
                                                    href="{{ action('Admin\CustomerController@assignPlan', [
                                                        "uid" => $customer->uid,
                                                        "plan_uid" => $plan->uid,
                                                    ]) }}"
                                                    class="btn btn-mc_primary btn-mc_mk mt-2 change-plan-button">
                                                        {{ trans('messages.plan.select') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        
                        </div>
                    </div>
                @endif
    
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

<script>
    $(function() {
        $('.change-plan-button').click(function(e) {
            e.preventDefault();

            var confirm = $(this).attr('data-confirm');
            var url = $(this).attr('href');
            var button = $(this);

            var dialog = new Dialog('confirm', {
                message: confirm,
                ok: function(dialog) {                    
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            gateway: button.closest('.submit-box').find('[name=gateway]').val(),
                        },
                        statusCode: {
                            // validate error
                            400: function (res) {
                                alert('Something went wrong!');
                            }
                        },
                        success: function (response) {
                            // notify
                            notify('success', '{{ trans('messages.notify.success') }}', response.message);

                            // redirect
                            tableRefreshAll()

                            // hide modal
                            assignPlanModal.hide();
                        }
                    });
                },
            });
        });
    });
</script>