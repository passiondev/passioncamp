<template>
    <div class="card mb-3">
        <header class="card-header">
            <h4>Student #{{ index + 1 }}</h4>
        </header>
        <div class="card-block ticket">
            <div class="row">
                <div class="form-group col-lg-6" :class="{ 'has-danger' : errors.has('first_name') }">
                    <label class="form-control-label" :for="`tickets_${index}__first_name`">First Name</label>
                    <input class="form-control" type="text" :id="`tickets_${index}__first_name`" v-model="form.first_name">
                    <div class="form-control-feedback" v-if="errors.has('first_name')">
                        {{ errors.first('first_name') }}
                    </div>
                </div>
                <div class="form-group col-lg-6" :class="{ 'has-danger' : errors.has('last_name') }">
                    <label class="form-control-label" :for="`tickets_${index}__last_name`">Last Name</label>
                    <input class="form-control" type="text" :id="`tickets_${index}__last_name`" v-model="form.last_name">
                    <div class="form-control-feedback" v-if="errors.has('last_name')">
                        {{ errors.first('last_name') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="form-control-label" :for="`tickets_${index}__email`">Email Address</label>
                    <input class="form-control" type="email" :id="`tickets_${index}__email`" v-model="form.email">
                </div>
                <div class="form-group col-lg-6">
                    <label class="form-control-label" :for="`tickets_${index}__phone`">Phone Number</label>
                    <input class="form-control" type="tel" :id="`tickets_${index}__phone`" v-model="form.phone">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12" :class="{ 'has-danger' : errors.has('gender') }">
                    <label class="form-control-label" :for="`tickets_${index}__gender`">Gender</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" value="M" v-model="form.gender" :id="`tickets_${index}__gender`"> Male
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" value="F" v-model="form.gender" :id="`tickets_${index}__gender`"> Female
                            </label>
                        </div>
                    </div>
                    <div class="form-control-feedback" v-if="errors.has('gender')">
                        {{ errors.first('gender') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4" :class="{ 'has-danger' : errors.has('grade') }">
                    <label class="form-control-label" :for="`tickets_${index}__grade`">Grade</label>
                    <select class="form-control" :id="`tickets_${index}__grade`" v-model="form.grade">
                        <option></option>
                        <option value="6" v-if="grades.includes(6)">6th</option>
                        <option value="7" v-if="grades.includes(7)">7th</option>
                        <option value="8" v-if="grades.includes(8)">8th</option>
                        <option value="9" v-if="grades.includes(9)">9th</option>
                        <option value="10" v-if="grades.includes(10)">10th</option>
                        <option value="11" v-if="grades.includes(11)">11th</option>
                        <option value="12" v-if="grades.includes(12)">12th</option>
                    </select>
                    <div class="form-control-feedback" v-if="errors.has('grade')">
                        {{ errors.first('grade') }}
                    </div>
                    <p class="form-text mb-0" style="line-height:1"><small class="text-muted">Grade completed as of Spring 2019.</small></p>
                </div>
                <div class="form-group col-lg-8">
                    <label class="form-control-label" :for="`tickets_${index}__school`">School</label>
                    <input class="form-control" type="text" :id="`tickets_${index}__school`" v-model="form.school">
                </div>
            </div>
            <div class="form-group">
                <label class="form-control-label" :for="`tickets_${index}__roommate_requested`">Roommate Requested <small class="text-muted">(optional)</small></label>
                <input class="form-control" type="text" v-model="form.roommate_requested" :id="`tickets_${index}__roommate_requested`">
                <p class="form-text mb-0" style="line-height:1"><small class="text-muted">Roommate requests will be considered but are not guaranteed.</small></p>
            </div>
            <div class="form-group">
                <ticket-considerations :considerations="form.considerations" @input="(considerations) => {form.considerations = considerations}"></ticket-considerations>
            </div>
        </div>
    </div>
</template>
<script>
    import { Errors } from 'form-backend-validation'
    import TicketConsiderations from './TicketConsiderations'

    export default {
        components: {TicketConsiderations},
        props: {
            index: Number,
            grades: {
                type: Array,
                default: () => {
                    return [6,7,8,9,10,11,12]
                }
            },
            errors: new Errors(),
        },
        data() {
            return {
                form: {
                    considerations: {},
                },
            };
        },
        watch: {
            form: {
                handler: function(form) {
                    this.$emit('input', form)
                },
                deep: true,
                immediate: true,
            },
        }
    }
</script>
