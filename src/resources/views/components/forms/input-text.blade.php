<input name='{{$name}}' class="form-control {{ value_if($errors->has($name),'is-invalid')}}"
       type="text" placeholder="{{$placeholder}}"
       value="{{old($name,$value)}}">

@error($name)
<div class="invalid-feedback">{{ join('',$errors->get($name)) }}</div>
@enderror


