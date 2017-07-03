package com.codingblocks.camera;

import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.Part;

/**
 * Created by ronaksakhuja on 01/07/17.
 */
public interface ApiInterface {
    @Multipart
    @POST("/imageupload.php?")
    Call<String> uploadimg(@Part("IMAGE") RequestBody IMAGE);
}
