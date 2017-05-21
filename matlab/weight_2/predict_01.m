
DATA = zeros(14,2,45);

count = 1;
for h = 1:9
    for k = h+1:10
        
        for j=1:14
            data = load([int2str(j+1991),'.txt']);
            DATA(j,1,count) = j+1991;
            DATA(j,2,count) = data(h,k);
        end
        count=count+1;
    end
end

result_forecast = zeros(4,1,45);
 for i=1:45
    Y1 = DATA(:,:,i);
    %Load tdu lieu va lay ra 10 quan sat
    du_lieu = Y1(1:10,2);
    %Ket qua tot nhat
    Y_best = 0;
    YMSE_best = 1;
    p_best = 1; d_best = 1; q_best = 1;
    %Chon lua model
%     for p = 1:2
%      for d = 1:2
%         for q = 1:3        
            %Khop model ARIMA(p,d,q) cho du lieu
            ChuoiARIMA = arima(2,1,1);
            Fit = estimate(ChuoiARIMA,du_lieu);

            %Du bao cho 4 nam tiep theo
            [Y,YMSE] = forecast(Fit,4,'Y0',du_lieu);

            %So sanh chi so thu duoc va chi so tot nhat
%             if (YMSE < YMSE_best)
%                 YMSE_best = YMSE;    Y_best = Y;
% %                 p_best = p; d_best = d; q_best = q;
%             end    
%         end        
%      end
%     end
%    Gia tri du bao cho tung cap node
    result_forecast(:,:,i) = Y;
 end

fileID = fopen('myfile.txt','w');
nbytes = fprintf(fileID,'%-15.3f %-15.3f %-15.3f %-15.3f\n',result_forecast);
fclose(fileID);
